<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\Consume\Ui\Component\Listing\Column\Status;

use Buzzi\Consume\Api\Data\DeliveryInterface;

class Options implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => DeliveryInterface::STATUS_PENDING, 'label' => __('Pending')],
            ['value' => DeliveryInterface::STATUS_DONE, 'label' => __('Done')],
            ['value' => DeliveryInterface::STATUS_FAIL, 'label' => __('Fail')]
        ];
    }
}
