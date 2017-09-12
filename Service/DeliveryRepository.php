<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\Consume\Service;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Buzzi\Consume\Api\Data\DeliveryInterface;

class DeliveryRepository implements \Buzzi\Consume\Api\DeliveryRepositoryInterface
{
    /**
     * @var \Buzzi\Consume\Api\Data\DeliveryInterfaceFactory
     */
    protected $deliveryFactory;

    /**
     * @var \Buzzi\Consume\Model\ResourceModel\Delivery
     */
    protected $deliveryResource;

    /**
     * @param \Buzzi\Consume\Api\Data\DeliveryInterfaceFactory $deliveryFactory
     * @param \Buzzi\Consume\Model\ResourceModel\Delivery $deliveryResource
     */
    public function __construct(
        \Buzzi\Consume\Api\Data\DeliveryInterfaceFactory $deliveryFactory,
        \Buzzi\Consume\Model\ResourceModel\Delivery $deliveryResource
    ) {
        $this->deliveryFactory = $deliveryFactory;
        $this->deliveryResource = $deliveryResource;
    }

    /**
     * @param mixed[] $data
     * @return \Buzzi\Consume\Api\Data\DeliveryInterface
     */
    public function getNew(array $data = [])
    {
        return $this->deliveryFactory->create($data);
    }

    /**
     * @param int $deliveryId
     * @return \Buzzi\Consume\Api\Data\DeliveryInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($deliveryId)
    {
        $delivery = $this->getNew();
        $this->deliveryResource->load($delivery, $deliveryId);
        if (!$delivery->getDeliveryId()) {
            throw new NoSuchEntityException(__('Delivery with id "%1" does not exist.', $deliveryId));
        }
        return $delivery;
    }

    /**
     * @param \Buzzi\Consume\Api\Data\DeliveryInterface $delivery
     * @return \Buzzi\Consume\Api\Data\DeliveryInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(DeliveryInterface $delivery)
    {
        try {
            $this->deliveryResource->save($delivery);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Could not save delivery: %1', $e->getMessage()));
        }
        return $delivery;
    }

    /**
     * @param \Buzzi\Consume\Api\Data\DeliveryInterface $delivery
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(DeliveryInterface $delivery)
    {
        try {
            $this->deliveryResource->delete($delivery);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__('Could not delete delivery: %1', $e->getMessage()));
        }
        return true;
    }

    /**
     * @param int $deliveryId
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function deleteById($deliveryId)
    {
        return $this->delete($this->getById($deliveryId));
    }
}
