<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\Consume\Model\Delivery;

use Buzzi\Consume\Api\Data\DeliveryInterface;

class PayloadPacker
{
    /**
     * @var \Buzzi\Base\Model\PayloadFile
     */
    protected $payloadFile;

    /**
     * @param \Buzzi\Base\Model\PayloadFile $payloadFile
     */
    public function __construct(
        \Buzzi\Base\Model\PayloadFile $payloadFile
    ) {
        $this->payloadFile = $payloadFile;
    }

    /**
     * @param \Buzzi\Consume\Api\Data\DeliveryInterface $delivery
     * @param array $payload
     * @return void
     */
    public function packPayload($delivery, array $payload)
    {
        $jsonPayload = json_encode($payload);
        $useFile = $delivery->getUseFile() || mb_strlen($jsonPayload) >= DeliveryInterface::MAX_PAYLOAD_LENGTH;
        $jsonPayload = $useFile ? $this->payloadFile->save($jsonPayload) : $jsonPayload;

        $delivery->setUseFile($useFile);
        $delivery->setPayload($jsonPayload);
    }

    /**
     * @param \Buzzi\Consume\Api\Data\DeliveryInterface $delivery
     * @return array
     */
    public function unpackPayload($delivery)
    {
        $jsonPayload = $delivery->getUseFile() ? $this->payloadFile->load($delivery->getPayload()) : $delivery->getPayload();

        return json_decode($jsonPayload, true);
    }

    /**
     * @param \Buzzi\Consume\Api\Data\DeliveryInterface $delivery
     * @return void
     */
    public function cleanPayload($delivery)
    {
        if ($delivery->getUseFile()) {
            $this->payloadFile->delete($delivery->getPayload());
            $delivery->setPayload('');
        }
    }
}
