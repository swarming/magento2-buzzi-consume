<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\Consume\Api;

interface PlatformInterface
{
    /**
     * @param int $maxQty
     * @param int $storeId
     * @return \Buzzi\Consume\Delivery[]
     */
    public function fetch($maxQty = 0, $storeId = null);

    /**
     * @param string $receipt
     * @param int $storeId
     * @return bool
     */
    public function confirm($receipt, $storeId);

    /**
     * @param string $receipt
     * @param mixed[] $errorData
     * @param int $storeId
     * @return bool
     */
    public function submitError($receipt, $errorData, $storeId);
}
