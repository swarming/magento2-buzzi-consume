<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\Consume\Model\ResourceModel\Delivery;

use Buzzi\Consume\Model\ResourceModel\Delivery as DeliveryResourceModel;
use Buzzi\Consume\Api\Data\DeliveryInterface;
use Buzzi\Consume\Model\Delivery;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Delivery::class, DeliveryResourceModel::class);
    }

    /**
     * @param int[] $deliveryIds
     * @return $this
     */
    public function filterDeliveryIds($deliveryIds)
    {
        $this->addFilter(DeliveryInterface::DELIVERY_ID, ['in' => $deliveryIds], 'public');
        return $this;
    }

    /**
     * @param int $storeId
     * @return $this
     */
    public function filterStore($storeId)
    {
        $this->addFilter(DeliveryInterface::STORE_ID, $storeId);
        return $this;
    }

    /**
     * @param string $eventType
     * @return $this
     */
    public function filterType($eventType)
    {
        $this->addFilter(DeliveryInterface::EVENT_TYPE, $eventType);
        return $this;
    }

    /**
     * @return $this
     */
    public function filterDone()
    {
        $this->addFieldToFilter(DeliveryInterface::STATUS, ['eq' => DeliveryInterface::STATUS_DONE]);
        return $this;
    }

    /**
     * @return $this
     */
    public function filterNotDone()
    {
        $this->addFieldToFilter(DeliveryInterface::STATUS, ['neq' => DeliveryInterface::STATUS_DONE]);
        return $this;
    }

    /**
     * @return $this
     */
    public function filterPending()
    {
        $this->addFilter(DeliveryInterface::STATUS, DeliveryInterface::STATUS_PENDING);
        return $this;
    }

    /**
     * @param int $days
     * @return $this
     */
    public function filterHandleTime($days)
    {
        $this->addFilter(
            DeliveryInterface::HANDLE_TIME,
            ['lteq' => new \Zend_Db_Expr(sprintf('DATE_SUB(NOW(), INTERVAL %d DAY)', $days))],
            'public'
        );
        return $this;
    }
}
