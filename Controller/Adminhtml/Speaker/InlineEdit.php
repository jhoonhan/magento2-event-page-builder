<?php declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Controller\Adminhtml\Speaker;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use HanStudio\EventPageBuilder\Model\Speaker;
use HanStudio\EventPageBuilder\Model\SpeakerFactory;
use HanStudio\EventPageBuilder\Model\ResourceModel\Speaker as SpeakerResource;
use Magento\Framework\Controller\ResultInterface;

class InlineEdit extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'HanStudio_EventPageBuilder::speaker_save';

    /**
     * InlineEdit constructor.
     *
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param SpeakerFactory $speakerFactory
     * @param SpeakerResource $speakerResource
     */
    public function __construct(
        Context                 $context,
        private JsonFactory     $jsonFactory,
        private SpeakerFactory  $speakerFactory,
        private SpeakerResource $speakerResource
    ) {
        parent::__construct($context);
    }

    /**
     * Execute action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $json = $this->jsonFactory->create();
        $messages = [];
        $error = false;
        $isAjax = $this->getRequest()->getParam('isAjax', false);
        $items = $this->getRequest()->getParam('items', []);

        if (!$isAjax || !count($items)) {
            $messages[] = __('Please correct the data sent.');
            $error = true;
        }

        if (!$error) {
            foreach ($items as $item) {
                $speaker_id = $item['speaker_id'];
                try {
                    /** @var Speaker $speaker */
                    $speaker = $this->speakerFactory->create();
                    $this->speakerResource->load($speaker, $speaker_id);
                    $speaker->setData(array_merge($speaker->getData(), $item));
                    $this->speakerResource->save($speaker);
                } catch (\Exception $e) {
                    $messages[] = __("Something went wrong while saving item $speaker_id");
                    $error = true;
                }
            }
        }

        return $json->setData([
            'messages' => $messages,
            'error' => $error,
        ]);
    }
}
