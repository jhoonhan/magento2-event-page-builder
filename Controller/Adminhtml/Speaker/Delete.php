<?php declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Controller\Adminhtml\Speaker;

use HanStudio\EventPageBuilder\Model\Speaker;
use HanStudio\EventPageBuilder\Model\SpeakerFactory;
use HanStudio\EventPageBuilder\Model\ResourceModel\Speaker as SpeakerResource;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;

class Delete extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'HanStudio_EventPageBuilder::speaker_delete';

    /**
     * Delete constructor.
     * @param Context $context
     * @param SpeakerFactory $speakerFactory
     * @param SpeakerResource $speakerResource
     */
    public function __construct(
        Context                 $context,
        private SpeakerFactory  $speakerFactory,
        private SpeakerResource $speakerResource
    ) {
        parent::__construct($context);
    }

    /**
     * Execute action based on request and return result
     *
     * @return Redirect
     */
    public function execute(): Redirect
    {
        $speaker = $this->speakerFactory->create();
        try {
            $speaker_id = $this->getRequest()->getParam('speaker_id');
            /** @var Speaker $speaker */
            $this->speakerResource->load($speaker, $speaker_id);
            if ($speaker->getData('speaker_id')) {
                $this->speakerResource->delete($speaker);
                $this->messageManager->addSuccessMessage(__('The record has been deleted.'));
            } else {
                $this->messageManager->addErrorMessage(__('The record does not exist.'));
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        /** @var Redirect $redirect */
        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        // On success, redirect back to the relevant event/edit page using the event_id
        return $redirect->setPath('*/event/edit/event_id/' . $speaker->getData('event_id'));
    }
}
