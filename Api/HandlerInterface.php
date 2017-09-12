<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\Consume\Api;

interface HandlerInterface
{
    /**
     * @param \Buzzi\Consume\Api\Data\DeliveryInterface $delivery
     * @return bool
     */
    public function handle($delivery);
}
