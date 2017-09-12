<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\Consume\Observer;

use Magento\Framework\Event\Observer;

class EventsCronSetup implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Buzzi\Consume\Model\Config\Events
     */
    protected $configEvents;

    /**
     * @var \Buzzi\Consume\Helper\EventsCronSetup
     */
    protected $cronSetupHelper;

    /**
     * @var \Magento\Framework\App\Config\ReinitableConfigInterface
     */
    protected $appConfig;

    /**
     * @param \Buzzi\Consume\Model\Config\Events $configEvents
     * @param \Buzzi\Consume\Helper\EventsCronSetup $cronSetupHelper
     * @param \Magento\Framework\App\Config\ReinitableConfigInterface $appConfig
     */
    public function __construct(
        \Buzzi\Consume\Model\Config\Events $configEvents,
        \Buzzi\Consume\Helper\EventsCronSetup $cronSetupHelper,
        \Magento\Framework\App\Config\ReinitableConfigInterface $appConfig
    ) {
        $this->configEvents = $configEvents;
        $this->cronSetupHelper = $cronSetupHelper;
        $this->appConfig = $appConfig;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(Observer $observer)
    {
        foreach ($this->configEvents->getAllTypes() as $eventType) {
            $this->cronSetupHelper->setup($eventType);
        }

        $this->appConfig->reinit();
    }
}

