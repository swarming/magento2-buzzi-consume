<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\Consume\Model;

use Magento\Framework\Model\AbstractExtensibleModel;
use Buzzi\Consume\Api\Data\DeliveryInterface;
use Buzzi\Consume\Model\ResourceModel\Delivery as DeliveryResourceModel;

class Delivery extends AbstractExtensibleModel implements DeliveryInterface
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(DeliveryResourceModel::class);
    }

    /**
     * @param int $deliveryId
     * @return $this
     */
    public function setDeliveryId($deliveryId)
    {
        return $this->setData(self::DELIVERY_ID, $deliveryId);
    }

    /**
     * @return int|null
     */
    public function getDeliveryId()
    {
        return $this->_getData(self::DELIVERY_ID);
    }

    /**
     * @param int $storeId
     * @return $this
     */
    public function setStoreId($storeId)
    {
        return $this->setData(self::STORE_ID, $storeId);
    }

    /**
     * @return int|null
     */
    public function getStoreId()
    {
        return $this->_getData(self::STORE_ID);
    }

    /**
     * @param string $eventType
     * @return $this
     */
    public function setEventType($eventType)
    {
        return $this->setData(self::EVENT_TYPE, $eventType);
    }

    /**
     * @return string
     */
    public function getEventType()
    {
        return $this->_getData(self::EVENT_TYPE);
    }

    /**
     * @param bool $useFile
     * @return $this
     */
    public function setUseFile($useFile)
    {
        return $this->setData(self::USE_FILE, $useFile);
    }

    /**
     * @return bool
     */
    public function getUseFile()
    {
        return $this->_getData(self::USE_FILE);
    }

    /**
     * @param string $payload
     * @return $this
     */
    public function setPayload($payload)
    {
        return $this->setData(self::PAYLOAD, $payload);
    }

    /**
     * @return string
     */
    public function getPayload()
    {
        return $this->_getData(self::PAYLOAD);
    }

    /**
     * @param int $counter
     * @return $this
     */
    public function setCounter($counter)
    {
        return $this->setData(self::COUNTER, $counter);
    }

    /**
     * @return int
     */
    public function getCounter()
    {
        return $this->_getData(self::COUNTER);
    }

    /**
     * @param string $receipt
     * @return $this
     */
    public function setReceipt($receipt)
    {
        return $this->setData(self::RECEIPT, $receipt);
    }

    /**
     * @return string
     */
    public function getReceipt()
    {
        return $this->_getData(self::RECEIPT);
    }

    /**
     * @param bool $isConfirmed
     * @return $this
     */
    public function setIsConfirmed($isConfirmed)
    {
        return $this->setData(self::IS_CONFIRMED, $isConfirmed);
    }

    /**
     * @return bool
     */
    public function getIsConfirmed()
    {
        return $this->_getData(self::IS_CONFIRMED);
    }

    /**
     * @param string $deliveryTime
     * @return $this
     */
    public function setDeliveryTime($deliveryTime)
    {
        return $this->setData(self::DELIVERY_TIME, $deliveryTime);
    }

    /**
     * @return string
     */
    public function getDeliveryTime()
    {
        return $this->_getData(self::DELIVERY_TIME);
    }

    /**
     * @param string $handleTime
     * @return $this
     */
    public function setHandleTime($handleTime)
    {
        return $this->setData(self::HANDLE_TIME, $handleTime);
    }

    /**
     * @return string
     */
    public function getHandleTime()
    {
        return $this->_getData(self::HANDLE_TIME);
    }

    /**
     * @param int $status
     * @return $this
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * @return int|null
     */
    public function getStatus()
    {
        return $this->_getData(self::STATUS);
    }

    /**
     * @param string $errorMessage
     * @return $this
     */
    public function setErrorMessage($errorMessage)
    {
        return $this->setData(self::ERROR_MESSAGE, $errorMessage);
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->_getData(self::ERROR_MESSAGE);
    }
}
