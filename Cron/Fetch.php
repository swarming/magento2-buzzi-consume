<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\Consume\Cron;

class Fetch
{
    /**
     * @var \Magento\Store\Api\StoreRepositoryInterface
     */
    protected $storeRepository;

    /**
     * @var \Buzzi\Consume\Model\Config\General
     */
    protected $configGeneral;

    /**
     * @var \Buzzi\Consume\Api\PlatformInterface
     */
    protected $platform;

    /**
     * @var \Buzzi\Consume\Api\QueueInterface
     */
    protected $queue;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @param \Magento\Store\Api\StoreRepositoryInterface $storeRepository
     * @param \Buzzi\Consume\Model\Config\General $configGeneral
     * @param \Buzzi\Consume\Api\PlatformInterface $platform
     * @param \Buzzi\Consume\Api\QueueInterface $queue
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Magento\Store\Api\StoreRepositoryInterface $storeRepository,
        \Buzzi\Consume\Model\Config\General $configGeneral,
        \Buzzi\Consume\Api\PlatformInterface $platform,
        \Buzzi\Consume\Api\QueueInterface $queue,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->storeRepository = $storeRepository;
        $this->configGeneral = $configGeneral;
        $this->platform = $platform;
        $this->queue = $queue;
        $this->logger = $logger;
    }

    /**
     * @return void
     */
    public function execute()
    {
        foreach ($this->storeRepository->getList() as $store) {
            if ($store->getId() == 0 || !$this->configGeneral->isEnabled($store->getId())) {
                continue;
            }

            $this->fetchDeliveries($store->getId());
        }
    }

    /**
     * @param int $storeId
     * @return void
     */
    protected function fetchDeliveries($storeId)
    {
        try {
            $deliveries = $this->platform->fetch($this->configGeneral->getMaxFetch($storeId), $storeId);
            $this->queue->addDeliveries($deliveries, $storeId);
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }
    }
}
