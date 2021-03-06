<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\Consume\Model\Config\Structure;

class Event extends \Magento\Framework\Config\Data
{
    /**
     * @param \Buzzi\Consume\Model\Config\Structure\Event\Reader $reader
     * @param \Magento\Framework\Config\CacheInterface $cache
     * @param string|null $cacheId
     */
    public function __construct(
        \Buzzi\Consume\Model\Config\Structure\Event\Reader $reader,
        \Magento\Framework\Config\CacheInterface $cache,
        $cacheId = 'buzzi_consume_events'
    ) {
        parent::__construct($reader, $cache, $cacheId);
    }
}
