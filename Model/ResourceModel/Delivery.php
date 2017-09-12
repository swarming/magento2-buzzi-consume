<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\Consume\Model\ResourceModel;

use Buzzi\Consume\Api\Data\DeliveryInterface;

class Delivery extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    const TABLE_NAME = 'buzzi_consume_queue';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, DeliveryInterface::DELIVERY_ID);
    }

    /**
     * @param \Buzzi\Consume\Model\Delivery|\Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    public function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        if ($object->getStatus() && $object->getStatus() != DeliveryInterface::STATUS_PENDING) {
            $object->setData(DeliveryInterface::HANDLE_TIME, new \Zend_Db_Expr('NOW()'));
        }

        return parent::_beforeSave($object);
    }
}
