<?php declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Controller\Adminhtml\Speaker;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NotFoundException;

use HanStudio\EventPageBuilder\Model\Speaker;
use HanStudio\EventPageBuilder\Model\SpeakerFactory;
use HanStudio\EventPageBuilder\Model\ResourceModel\Speaker as SpeakerResource;
use HanStudio\EventPageBuilder\Model\ImageUploader;

class Save extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'HanStudio_EventPageBuilder::speaker_save';

    /** @var int */
    private int $event_id;

    /**
     * @param Context $context
     * @param SpeakerFactory $speakerFactory
     * @param SpeakerResource $speakerResource
     * @param ImageUploader $imageUploader
     */
    public function __construct(
        Context                 $context,
        private SpeakerFactory  $speakerFactory,
        private SpeakerResource $speakerResource,
        private ImageUploader   $imageUploader,
    ) {
        parent::__construct($context);
        $this->event_id = 0;
    }

    /**
     * Save action
     *
     * @return Redirect
     */
    public function execute(): Redirect
    {

//        Creates redirect page
        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

//        Get post data
        $post = $this->getRequest()->getPost();

        //        Sets event_id
        $this->event_id = (int)$this->getRequest()->getParam('event_id');

        $isExistingPost = $post->speaker_id;
        /**
         * Creates new speaker object
         * @var Speaker $speaker
         */
        $speaker = $this->speakerFactory->create();

        if ($isExistingPost) {
            try {
                $this->speakerResource->load($speaker, $post->speaker_id);
                if (!$speaker->getData('speaker_id')) {
                    throw new NotFoundException(__('This record no longer exists.'));
                }
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $redirect->setPath('*/*/');
            }
        } else {
            // If new, build an object with the posted data to save it
            unset($post->speaker_id);
        }

        //        Sets data and image data
        $speaker->setData(array_merge($speaker->getData(), $post->toArray()));
        $this->imageUploader->setImageData($speaker, $post->toArray());

        try {
            $this->speakerResource->save($speaker);
            $this->messageManager->addSuccessMessage(__('The record has been saved.'));
        } catch (\Exception $e) {
            $errorMsg = 'There was a problem saving the record. ERROR_SPEAKER_SAVE_PHP_109';
            $this->messageManager->addErrorMessage(__($errorMsg . $e));
            return $redirect->setPath('*/*/');
        }

        // On success, redirect back to the relevant event/edit page using the event_id
        return $redirect->setPath('*/event/edit/event_id/' . $speaker->getData('event_id'));
    }
}
