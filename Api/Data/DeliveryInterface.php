<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\Consume\Api\Data;

use Magento\Framework\DB\Ddl\Table as DBTable;

interface DeliveryInterface
{
    const STATUS_PENDING = 'pending';
    const STATUS_FAIL = 'fail';
    const STATUS_DONE = 'done';

    const DELIVERY_ID = 'delivery_id';
    const STORE_ID = 'store_id';
    const EVENT_TYPE = 'event_type';
    const USE_FILE = 'use_file';
    const PAYLOAD = 'payload';
    const COUNTER = 'counter';
    const RECEIPT = 'receipt';
    const IS_CONFIRMED = 'is_confirmed';
    const DELIVERY_TIME = 'delivery_time';
    const HANDLE_TIME = 'handle_time';
    const STATUS = 'status';
    const ERROR_MESSAGE = 'error_message';

    const MAX_PAYLOAD_LENGTH = DBTable::MAX_TEXT_SIZE;

    /**
     * @param int $deliveryId
     * @return $this
     */
    public function setDeliveryId($deliveryId);

    /**
     * @return int|null
     */
    public function getDeliveryId();

    /**
     * @param int $storeId
     * @return $this
     */
    public function setStoreId($storeId);

    /**
     * @return int|null
     */
    public function getStoreId();

    /**
     * @param string $eventType
     * @return $this
     */
    public function setEventType($eventType);

    /**
     * @return string
     */
    public function getEventType();

    /**
     * @param bool $useFile
     * @return $this
     */
    public function setUseFile($useFile);

    /**
     * @return bool
     */
    public function getUseFile();

    /**
     * @param string $payload
     * @return $this
     */
    public function setPayload($payload);

    /**
     * @return string
     */
    public function getPayload();

    /**
     * @param int $counter
     * @return $this
     */
    public function setCounter($counter);

    /**
     * @return int
     */
    public function getCounter();

    /**
     * @param string $receipt
     * @return $this
     */
    public function setReceipt($receipt);

    /**
     * @return string
     */
    public function getReceipt();

    /**
     * @param bool $isConfirmed
     * @return $this
     */
    public function setIsConfirmed($isConfirmed);

    /**
     * @return bool
     */
    public function getIsConfirmed();

    /**
     * @param string $deliveryTime
     * @return $this
     */
    public function setDeliveryTime($deliveryTime);

    /**
     * @return string
     */
    public function getDeliveryTime();

    /**
     * @param string $handleTime
     * @return $this
     */
    public function setHandleTime($handleTime);

    /**
     * @return string
     */
    public function getHandleTime();

    /**
     * @param int $status
     * @return $this
     */
    public function setStatus($status);

    /**
     * @return int|null
     */
    public function getStatus();

    /**
     * @param string $errorMessage
     * @return $this
     */
    public function setErrorMessage($errorMessage);

    /**
     * @return string
     */
    public function getErrorMessage();
}
