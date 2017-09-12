<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\Consume\Model\Config\Structure\Event;

class Reader extends \Magento\Framework\Config\Reader\Filesystem
{
    /**
     * Xml merging attributes
     *
     * @var array
     */
    protected $_idAttributes = [
        '/config/buzzi/consume_event' => 'code'
    ];

    /**
     * @param \Magento\Framework\Config\FileResolverInterface $fileResolver
     * @param \Buzzi\Consume\Model\Config\Structure\Event\Converter $converter
     * @param \Buzzi\Consume\Model\Config\Structure\Event\SchemaLocator $schemaLocator
     * @param \Magento\Framework\Config\ValidationStateInterface $validationState
     * @codeCoverageIgnore
     */
    public function __construct(
        \Magento\Framework\Config\FileResolverInterface $fileResolver,
        \Buzzi\Consume\Model\Config\Structure\Event\Converter $converter,
        \Buzzi\Consume\Model\Config\Structure\Event\SchemaLocator $schemaLocator,
        \Magento\Framework\Config\ValidationStateInterface $validationState
    ) {
        parent::__construct(
            $fileResolver,
            $converter,
            $schemaLocator,
            $validationState,
            'consume_events.xml',
            []
        );
    }
}
