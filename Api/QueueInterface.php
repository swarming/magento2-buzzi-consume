<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\Consume\Api;

interface QueueInterface
{
    /**
     * @param \Buzzi\Consume\Delivery[] $platformDeliveries
     * @param int|string $storeId
     * @return void
     */
    public function addDeliveries(array $platformDeliveries, $storeId);

    /**
     * @param int[] $deliveryIds
     * @return int
     */
    public function handleByIds(array $deliveryIds);

    /**
     * @param string $eventType
     * @param int|string $storeId
     * @return int
     */
    public function handleByType($eventType, $storeId = null);

    /**
     * @param int $delay
     * @param int|string $storeId
     * @return void
     */
    public function deleteDone($delay, $storeId = null);

    /**
     * @param int[] $deliveryIds
     * @return void
     */
    public function deleteByIds(array $deliveryIds);
}
