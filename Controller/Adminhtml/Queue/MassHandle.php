<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\Consume\Controller\Adminhtml\Queue;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;

class MassHandle extends \Magento\Backend\App\Action
{
    /**
     * @var \Buzzi\Consume\Api\QueueInterface
     */
    protected $queue;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Buzzi\Consume\Api\QueueInterface $queue
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Buzzi\Consume\Api\QueueInterface $queue
    ) {
        $this->queue = $queue;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('buzzi_consume/queue/index');

        $excluded = $this->getRequest()->getParam('excluded') === 'false' ? false : true;
        $deliveryIds = (array)$this->getRequest()->getParam('selected');
        if (empty($deliveryIds) && $excluded) {
            $this->messageManager->addWarningMessage(__("You haven't selected any item!"));
            return $resultRedirect;
        }

        try {
            $count = $this->queue->handleByIds($deliveryIds);
            $this->messageManager->addSuccessMessage(__('%1 deliveries were handled successfully.', $count));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('An error occurred while handling deliveries.'));
        }

        return $resultRedirect;
    }
}
