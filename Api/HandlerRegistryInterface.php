<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\Consume\Api;

interface HandlerRegistryInterface
{
    /**
     * @param string $eventType
     * @return \Buzzi\Consume\Api\HandlerInterface
     * @throws \DomainException
     * @throws \InvalidArgumentException
     */
    public function getHandler($eventType);
}
