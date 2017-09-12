<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\Consume\Model\Config\System\Source;

class EventType implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \Buzzi\Consume\Model\Config\Events
     */
    protected $configEvents;

    /**
     * @param \Buzzi\Consume\Model\Config\Events $configEvents
     */
    public function __construct(
        \Buzzi\Consume\Model\Config\Events $configEvents
    ) {
        $this->configEvents = $configEvents;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];
        foreach ($this->configEvents->getAllTypes() as $eventType) {
            $options[] = [
                'label' => $this->configEvents->getEventLabel($eventType),
                'value' => $eventType,
            ];
        }
        return $options;
    }
}
