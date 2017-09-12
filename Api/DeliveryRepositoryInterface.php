<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\Consume\Api;

use Buzzi\Consume\Api\Data\DeliveryInterface;

interface DeliveryRepositoryInterface
{
    /**
     * @param mixed[] $data
     * @return \Buzzi\Consume\Api\Data\DeliveryInterface
     */
    public function getNew(array $data = []);

    /**
     * @param int $deliveryId
     * @return \Buzzi\Consume\Api\Data\DeliveryInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($deliveryId);

    /**
     * @param \Buzzi\Consume\Api\Data\DeliveryInterface $delivery
     * @return \Buzzi\Consume\Api\Data\DeliveryInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(DeliveryInterface $delivery);

    /**
     * @param \Buzzi\Consume\Api\Data\DeliveryInterface $delivery
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(DeliveryInterface $delivery);

    /**
     * @param int $deliveryId
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function deleteById($deliveryId);
}
