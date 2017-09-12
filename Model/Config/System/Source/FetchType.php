<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\Consume\Model\Config\System\Source;

class FetchType implements \Magento\Framework\Option\ArrayInterface
{
    const ENABLED = 'enabled';
    const REGISTERED = 'registered';
    const ALL = 'all';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            self::ENABLED => __('Enabled Only'),
            self::REGISTERED => __('Registered Only'),
            self::ALL => __('All Events'),
        ];
    }
}
