<?php declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Controller\Adminhtml\Event;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use HanStudio\EventPageBuilder\Model\Event;
use HanStudio\EventPageBuilder\Model\EventFactory;
use HanStudio\EventPageBuilder\Model\ResourceModel\Event as EventResource;
use HanStudio\EventPageBuilder\Controller\Adminhtml\Event\Save as EventSave;
use HanStudio\EventPageBuilder\Model\Config\Source\DateTimeFormat;

use HanStudio\EventPageBuilder\Controller\Adminhtml\BlockActions;
use Magento\Framework\Controller\ResultInterface;

class InlineEdit extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'HanStudio_EventPageBuilder::event_save';

    /**
     * InlineEdit constructor.
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param EventFactory $eventFactory
     * @param EventResource $eventResource
     * @param EventSave $eventSave
     * @param BlockActions $blockActions
     * @param DateTimeFormat $dateTimeFormat
     */
    public function __construct(
        private Context        $context,
        private JsonFactory    $jsonFactory,
        private EventFactory   $eventFactory,
        private EventResource  $eventResource,
        private EventSave      $eventSave,
        private BlockActions   $blockActions,
        private DateTimeFormat $dateTimeFormat
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
        $posts = $this->getRequest()->getParam('items', []);

        if (!$isAjax || !count($posts)) {
            $messages[] = __('Please correct the data sent.');
            $error = true;
        }

        if (!$error) {
            foreach ($posts as $post) {
                $event_id = $post['event_id'];
                try {
                    /** @var Event $event */
                    $event = $this->eventFactory->create();
                    $this->eventResource->load($event, $event_id);
                    //  E010: Detect url change
                    $this->blockActions->updateCmsBlock($event, $post);
                    //  E009: Detect timezone change
                    //  $orgTimezone = $event->getData('timezone');
                    //  $newTimezone = $post['timezone'];
                    //  $this->dateTimeFormat->reformatTimes($event_id, $orgTimezone, $newTimezone);

                    $event->setData(array_merge($event->getData(), $post));
                    $this->eventResource->save($event);
                } catch (\Exception $e) {
                    $messages[] = __("Something went wrong while saving item $event_id");
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
