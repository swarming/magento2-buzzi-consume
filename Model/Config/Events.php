<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\Consume\Model\Config;

use Magento\Store\Model\ScopeInterface;

class Events
{
    /**
     * @var \Buzzi\Consume\Model\Config\General
     */
    protected $configGeneral;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var mixed[]
     */
    protected $eventsConfigData;

    /**
     * @param \Buzzi\Consume\Model\Config\General $configGeneral
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Buzzi\Consume\Model\Config\Structure\Event $eventsStructure
     */
    public function __construct(
        \Buzzi\Consume\Model\Config\General $configGeneral,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Buzzi\Consume\Model\Config\Structure\Event $eventsStructure
    ) {
        $this->configGeneral = $configGeneral;
        $this->eventsConfigData = $eventsStructure->get();
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return string[]
     */
    public function getAllTypes()
    {
        $allTypes = array_keys($this->eventsConfigData);
        sort($allTypes);
        return $allTypes;
    }

    /**
     * @param string $type
     * @param int|string|null $storeId
     * @return bool
     */
    public function isEventEnabled($type, $storeId = null)
    {
        return $this->configGeneral->isEnabled($storeId)
            && isset($this->eventsConfigData[$type])
            && in_array($type, $this->configGeneral->getEnabledEvents($storeId));
    }

    /**
     * @param string $type
     * @return string
     */
    public function getEventCode($type)
    {
        return $this->getEventConfigValue($type, 'code');
    }

    /**
     * @param string $type
     * @return string
     */
    public function getEventLabel($type)
    {
        return $this->getEventConfigValue($type, 'label') ?: $type;
    }

    /**
     * @param string $type
     * @return string|null
     */
    public function getHandler($type)
    {
        return $this->getEventConfigValue($type, 'handler');
    }

    /**
     * @param string $type
     * @return bool
     */
    public function isGlobalSchedule($type)
    {
        return (bool)$this->getValue($type, 'global_schedule');
    }

    /**
     * @param string $type
     * @return bool
     */
    public function isCustomSchedule($type)
    {
        return (bool)$this->getValue($type, 'custom_schedule');
    }

    /**
     * @param string $type
     * @return string
     */
    public function getCronSchedule($type)
    {
        return $this->getValue($type, 'cron_schedule');
    }

    /**
     * @param string $type
     * @return int[]
     */
    public function getCronStartTime($type)
    {
        $time = $this->getValue($type, 'cron_start_time');
        return $time ? explode(',', $time) : [];
    }

    /**
     * @param string $type
     * @return string
     */
    public function getCronFrequency($type)
    {
        return $this->getValue($type, 'cron_frequency');
    }

    /**
     * @param string $type
     * @param string $field
     * @param int|string|null $storeId
     * @return string
     */
    public function getValue($type, $field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            "buzzi_consume_events/{$this->getEventCode($type)}/{$field}",
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param string $type
     * @param string $field
     * @param int|string|null $storeId
     * @return string
     */
    public function getFlag($type, $field, $storeId = null)
    {
        return $this->scopeConfig->isSetFlag(
            "buzzi_consume_events/{$this->getEventCode($type)}/{$field}",
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param string $type
     * @param string $field
     * @return mixed|null
     * @throws \DomainException
     */
    protected function getEventConfigValue($type, $field)
    {
        if (!isset($this->eventsConfigData[$type])) {
            throw new \DomainException(sprintf('"%s" consume event is not supported.', $type));
        }

        return isset($this->eventsConfigData[$type][$field])
            ? $this->eventsConfigData[$type][$field]
            : null;
    }
}
