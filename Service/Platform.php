<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\Consume\Service;

class Platform implements \Buzzi\Consume\Api\PlatformInterface
{
    /**
     * @var \Buzzi\Base\Platform\Registry
     */
    protected $platformRegistry;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @param \Buzzi\Base\Platform\Registry $platformRegistry
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Buzzi\Base\Platform\Registry $platformRegistry,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->platformRegistry = $platformRegistry;
        $this->logger = $logger;
    }

    /**
     * @param int|null $storeId
     * @return \Buzzi\Consume\ConsumeService
     */
    protected function getConsumeService($storeId)
    {
        return $this->platformRegistry->getSdk($storeId)->getConsumeService();
    }

    /**
     * @param int $maxQty
     * @param int $storeId
     * @return \Buzzi\Consume\Delivery[]
     */
    public function fetch($maxQty = 0, $storeId = null)
    {
        list($deliveries, $exceptions) = $this->getConsumeService($storeId)->batchFetch($maxQty);
        $this->logExceptions($exceptions);
        return $deliveries;
    }

    /**
     * @param \Exception[] $exceptions
     * @return void
     */
    protected function logExceptions($exceptions)
    {
        foreach ($exceptions as $exception) {
            $this->logger->critical($exception);
        }
    }

    /**
     * @param string $receipt
     * @param int $storeId
     * @return bool
     */
    public function confirm($receipt, $storeId)
    {
        try {
            $result = $this->getConsumeService($storeId)->confirm($receipt);
        } catch (\Exception $e) {
            $result = false;
            $this->logger->critical($e);
        }

        return $result;
    }

    /**
     * @param string $receipt
     * @param array $errorData
     * @param int $storeId
     * @return bool
     */
    public function submitError($receipt, $errorData, $storeId)
    {
        try {
            $result = $this->getConsumeService($storeId)->submitError($receipt, $errorData);
        } catch (\Exception $e) {
            $result = false;
            $this->logger->critical($e);
        }

        return $result;
    }
}
