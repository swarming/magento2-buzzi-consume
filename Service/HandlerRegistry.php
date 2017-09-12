<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\Consume\Service;

class HandlerRegistry implements \Buzzi\Consume\Api\HandlerRegistryInterface
{
    /**
     * @var \Buzzi\Consume\Model\Config\Events
     */
    protected $configEvents;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var \Buzzi\Consume\Api\HandlerInterface[]
     */
    protected $handlers = [];

    /**
     * @param \Buzzi\Consume\Model\Config\Events $configEvents
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(
        \Buzzi\Consume\Model\Config\Events $configEvents,
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->configEvents = $configEvents;
        $this->objectManager = $objectManager;
    }

    /**
     * @param string $eventType
     * @return \Buzzi\Consume\Api\HandlerInterface
     */
    public function getHandler($eventType)
    {
        $handlerClass = $this->configEvents->getHandler($eventType);
        if (empty($handlerClass)) {
            throw new \DomainException(sprintf('Event handler is not found for "%s" event type.', $eventType));
        }

        if (empty($this->handlers[$handlerClass])) {
            $this->handlers[$handlerClass] = $this->createHandler($handlerClass);
        }
        return $this->handlers[$handlerClass];
    }

    /**
     * @param string $handlerClass
     * @return \Buzzi\Consume\Api\HandlerInterface
     */
    protected function createHandler($handlerClass)
    {
        $handler = $this->objectManager->create($handlerClass);

        if (!$handler instanceof \Buzzi\Consume\Api\HandlerInterface) {
            throw new \InvalidArgumentException(get_class($handler) . ' must be an instance of \Buzzi\Consume\Api\HandlerInterface.');
        }

        return $handler;
    }
}
