<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\Consume\Model\Config\Structure\Event;

class Converter implements \Magento\Framework\Config\ConverterInterface
{
    /**
     * @param \DOMDocument $source
     * @return array
     */
    public function convert($source)
    {
        $output = [];

        /** @var \DOMElement[] $buzziNodes */
        $buzziNodes = $source->getElementsByTagName('buzzi');

        foreach ($buzziNodes as $buzziNode) {
            /** @var \DOMElement $consumeEvent */
            foreach ($buzziNode->childNodes as $consumeEvent) {
                if ($consumeEvent->nodeName != 'consume_event') {
                    continue;
                }

                $eventData = $this->convertEvent($consumeEvent);

                if (empty($eventData['type'])) {
                    throw new \InvalidArgumentException('Event "type" does not exist');
                }

                $output[$eventData['type']] = $eventData;
            }
        }

        return $output;
    }

    /**
     * @param \DOMElement $consumeEvent
     * @return mixed[]
     */
    protected function convertEvent($consumeEvent)
    {
        if (!$consumeEvent->hasAttribute('code')) {
            throw new \InvalidArgumentException('Event attribute "code" does not exist');
        }

        $eventData = [];
        $eventData['code'] = $consumeEvent->getAttribute('code');

        foreach ($consumeEvent->childNodes as $childNode) {
            if ($childNode->nodeName == 'label') {
                $eventData['label'] = $childNode->nodeValue;
            }
            if ($childNode->nodeName == 'type') {
                $eventData['type'] = $childNode->nodeValue;
            }
            if ($childNode->nodeName == 'handler') {
                $eventData['handler'] = $childNode->nodeValue;
            }
        }

        return $eventData;
    }
}
