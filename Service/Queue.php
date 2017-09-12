<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\Consume\Service;

use Buzzi\Consume\Api\Data\DeliveryInterface;
use Buzzi\Consume\Model\Config\System\Source\FetchType;

class Queue implements \Buzzi\Consume\Api\QueueInterface
{
    /**
     * @var \Buzzi\Consume\Model\Config\General
     */
    protected $configGeneral;

    /**
     * @var \Buzzi\Consume\Model\Config\Events
     */
    protected $configEvents;

    /**
     * @var \Buzzi\Consume\Api\PlatformInterface
     */
    protected $platform;

    /**
     * @var \Buzzi\Consume\Model\Delivery\PayloadPacker
     */
    protected $payloadPacker;

    /**
     * @var \Buzzi\Consume\Api\HandlerRegistryInterface
     */
    protected $handlerRegistry;

    /**
     * @var \Buzzi\Consume\Api\DeliveryRepositoryInterface
     */
    protected $deliveryRepository;

    /**
     * @var \Buzzi\Consume\Model\ResourceModel\Delivery\CollectionFactory
     */
    protected $deliveryCollectionFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @param \Buzzi\Consume\Model\Config\General $configGeneral
     * @param \Buzzi\Consume\Model\Config\Events $configEvents
     * @param \Buzzi\Consume\Api\PlatformInterface $platform
     * @param \Buzzi\Consume\Model\Delivery\PayloadPacker $payloadPacker
     * @param \Buzzi\Consume\Api\HandlerRegistryInterface $handlerRegistry
     * @param \Buzzi\Consume\Api\DeliveryRepositoryInterface $deliveryRepository
     * @param \Buzzi\Consume\Model\ResourceModel\Delivery\CollectionFactory $deliveryCollectionFactory
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Buzzi\Consume\Model\Config\General $configGeneral,
        \Buzzi\Consume\Model\Config\Events $configEvents,
        \Buzzi\Consume\Api\PlatformInterface $platform,
        \Buzzi\Consume\Model\Delivery\PayloadPacker $payloadPacker,
        \Buzzi\Consume\Api\HandlerRegistryInterface $handlerRegistry,
        \Buzzi\Consume\Api\DeliveryRepositoryInterface $deliveryRepository,
        \Buzzi\Consume\Model\ResourceModel\Delivery\CollectionFactory $deliveryCollectionFactory,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->configGeneral = $configGeneral;
        $this->configEvents = $configEvents;
        $this->platform = $platform;
        $this->payloadPacker = $payloadPacker;
        $this->handlerRegistry = $handlerRegistry;
        $this->deliveryRepository = $deliveryRepository;
        $this->deliveryCollectionFactory = $deliveryCollectionFactory;
        $this->logger = $logger;
    }

    /**
     * @param \Buzzi\Consume\Delivery[] $platformDeliveries
     * @param int|string $storeId
     * @return void
     */
    public function addDeliveries(array $platformDeliveries, $storeId)
    {
        foreach ($platformDeliveries as $platformDelivery) {
            if (!$this->canSaveDelivery($platformDelivery->getEventType(), $storeId)) {
                continue;
            }

            $delivery = $this->deliveryRepository->getNew();
            $delivery->setStoreId($storeId);
            $delivery->setEventType($platformDelivery->getEventType());
            $this->payloadPacker->packPayload($delivery, (array)$platformDelivery->getBody());
            $delivery->setReceipt($platformDelivery->getReceipt());
            $delivery->setStatus(DeliveryInterface::STATUS_PENDING);
            $this->deliveryRepository->save($delivery);
        }
    }

    /**
     * @param string|null $eventType
     * @param int $storeId
     * @return bool
     */
    protected function canSaveDelivery($eventType, $storeId)
    {
        $fetchAll = $this->configGeneral->getFetchType($storeId) == FetchType::ALL;
        $fetchRegistered = $this->configGeneral->getFetchType($storeId) == FetchType::REGISTERED;
        $fetchEnabled = $this->configGeneral->getFetchType($storeId) == FetchType::ENABLED;

        return $fetchAll
            || ($fetchRegistered && in_array($fetchRegistered, $this->configEvents->getAllTypes()))
            || ($fetchEnabled && $this->configEvents->isEventEnabled($eventType, $storeId));
    }

    /**
     * @param int[] $deliveryIds
     * @return int
     */
    public function handleByIds(array $deliveryIds)
    {
        $deliveries = $this->deliveryCollectionFactory->create();
        if ($deliveryIds) {
            $deliveries->filterDeliveryIds($deliveryIds);
        }

        return $this->handleDeliveries($deliveries);
    }

    /**
     * @param \Buzzi\Consume\Model\ResourceModel\Delivery\Collection $deliveries
     * @return int
     */
    protected function handleDeliveries($deliveries)
    {
        $counter = 0;
        foreach ($deliveries as $delivery) {
            try {
                $counter += $this->handle($delivery) ? 1 : 0;
            } catch (\Exception $e) {
                $this->logger->critical($e);
            }
        }
        return $counter;
    }

    /**
     * @param \Buzzi\Consume\Api\Data\DeliveryInterface $delivery
     * @return bool
     */
    protected function handle($delivery)
    {
        if (DeliveryInterface::STATUS_DONE == $delivery->getStatus()) {
            throw new \InvalidArgumentException(sprintf('Delivery with %s ID is already processed.', $delivery->getDeliveryId()));
        }

        $isProcessed = false;
        $isConfirmed = false;
        $errorMessage = null;
        $errorData = [];

        try {
            $handler = $this->handlerRegistry->getHandler($delivery->getEventType());
            $isProcessed = $handler->handle($delivery);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            $errorData = [
                'message' => $errorMessage,
                'trace' => $e->getTraceAsString()
            ];
        }

        if ($isProcessed) {
            $isConfirmed = $this->platform->confirm($delivery->getReceipt(), $delivery->getStoreId());
        } else {
            $this->platform->submitError($delivery->getReceipt(), $errorData, $delivery->getStoreId());
        }

        if ($isProcessed && $this->configGeneral->isRemoveImmediately($delivery->getStoreId())) {
            $this->deleteDelivery($delivery);
        } else {
            $this->updateDelivery($delivery, $isProcessed, $isConfirmed, $errorMessage);
        }

        return $isProcessed;
    }

    /**
     * @param \Buzzi\Consume\Api\Data\DeliveryInterface $delivery
     * @param bool $isProcessed
     * @param bool $isConfirmed
     * @param string|null $errorMessage
     * @return void
     */
    protected function updateDelivery($delivery, $isProcessed, $isConfirmed, $errorMessage)
    {
        if ($isProcessed) {
            $delivery->setIsConfirmed($isConfirmed);
            $delivery->setStatus(DeliveryInterface::STATUS_DONE);
            $delivery->setErrorMessage('');
        } else {
            $delivery->setStatus(DeliveryInterface::STATUS_FAIL);
            $delivery->setErrorMessage($errorMessage);
        }

        $count = $delivery->getCounter();
        $delivery->setCounter(++$count);

        $this->deliveryRepository->save($delivery);
    }

    /**
     * @param string $eventType
     * @param int|string $storeId
     * @return int
     */
    public function handleByType($eventType, $storeId = null)
    {
        $deliveries = $this->deliveryCollectionFactory->create();
        $deliveries->filterType($eventType);
        $deliveries->filterPending();
        if ($storeId) {
            $deliveries->filterStore($storeId);
        }

        return $this->handleDeliveries($deliveries);
    }

    /**
     * @param int $delay
     * @param int|string $storeId
     * @return void
     */
    public function deleteDone($delay, $storeId = null)
    {
        $deliveries = $this->deliveryCollectionFactory->create();
        $deliveries->filterDone();
        $deliveries->filterHandleTime($delay);
        if ($storeId) {
            $deliveries->filterStore($storeId);
        }

        /** @var \Buzzi\Consume\Api\Data\DeliveryInterface $delivery */
        foreach ($deliveries as $delivery) {
            $this->deleteDelivery($delivery);
        };
    }

    /**
     * @param int[] $deliveryIds
     * @return void
     */
    public function deleteByIds(array $deliveryIds)
    {
        $deliveries = $this->deliveryCollectionFactory->create();
        if ($deliveryIds) {
            $deliveries->filterDeliveryIds($deliveryIds);
        }

        /** @var \Buzzi\Consume\Api\Data\DeliveryInterface $delivery */
        foreach ($deliveries as $delivery) {
            $this->deleteDelivery($delivery);
        };
    }

    /**
     * @param \Buzzi\Consume\Api\Data\DeliveryInterface $delivery
     * @return void
     */
    protected function deleteDelivery($delivery)
    {
        $this->payloadPacker->cleanPayload($delivery);
        $this->deliveryRepository->delete($delivery);
    }
}
