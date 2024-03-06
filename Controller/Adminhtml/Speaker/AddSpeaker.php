<?php declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Controller\Adminhtml\Speaker;

use HanStudio\EventPageBuilder\Model\Speaker;
use HanStudio\EventPageBuilder\Model\SpeakerFactory;
use HanStudio\EventPageBuilder\Model\ResourceModel\Speaker\CollectionFactory as SpeakerCollectionFactory;
use HanStudio\EventPageBuilder\Model\ResourceModel\Speaker as SpeakerResource;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Framework\App\Action\HttpPostActionInterface;

class AddSpeaker extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'HanStudio_EventPageBuilder::speaker_save';

    /**
     * @param Context $context
     * @param SpeakerFactory $speakerFactory
     * @param SpeakerResource $speakerResource
     * @param SpeakerCollectionFactory $speakerCollectionFactory
     * @param Filter $filter
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context                          $context,
        private SpeakerFactory           $speakerFactory,
        private SpeakerResource          $speakerResource,
        private SpeakerCollectionFactory $speakerCollectionFactory,
        private Filter                   $filter,
        private JsonFactory              $jsonFactory
    ) {

        parent::__construct($context);
    }

    /**
     * K007: Controller for adding a speaker within the schedule form.
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $json = $this->jsonFactory->create();
        $messages = [];
        $itemIds = [];
        $items = [];
        $isAjax = $this->getRequest()->getParam('isAjax', false);

        if (!$isAjax) {
            return $json->setData([
                'messages' => ['Not an Ajax request.'],
            ]);
        }

        $posts = $this->getRequest()->getParam('items', []);
        if (empty($posts)) {
            return $json->setData([
                'messages' => ['No data to save.'],
            ]);
        }

        foreach ($posts as $post) {
            /** @var Speaker $speaker */
            $speaker = $this->speakerFactory->create();
            $speaker->setData($post);
            try {
                $this->speakerResource->save($speaker);
                $itemIds[] = $speaker->getData('speaker_id');
                $items[] = $speaker->getData();
                $messages[] = __('Item saved successfully.');
            } catch (\Exception $e) {
                $messages[] = __("Something went wrong while saving item. %1", $e->getMessage());
            }
        }

        // On success, returns the data
        return $json->setData([
            'messages' => $messages,
            'itemIds' => $itemIds,
            'items' => $items
        ]);
    }
}
