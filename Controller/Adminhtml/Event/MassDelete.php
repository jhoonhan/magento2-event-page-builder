<?php declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Controller\Adminhtml\Event;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use HanStudio\EventPageBuilder\Model\ResourceModel\Event\CollectionFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Ui\Component\MassAction\Filter;
use HanStudio\EventPageBuilder\Controller\Adminhtml\BlockActions;

class MassDelete extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'HanStudio_EventPageBuilder::event_delete';

    /**
     * MassDelete constructor.
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     * @param Filter $filter
     * @param BlockActions $blockActions
     */
    public function __construct(
        Context                   $context,
        private CollectionFactory $collectionFactory,
        private Filter            $filter,
        private BlockActions      $blockActions
    ) {
        parent::__construct($context);
    }

    /**
     * Execute action
     *
     * @return Redirect
     */
    public function execute(): Redirect
    {
        try {
            $collection = $this->collectionFactory->create();
            $items = $this->filter->getCollection($collection);
            $itemsSize = $items->getSize();

            foreach ($items as $item) {
                $item->delete();
                //  E010-1: Delete corresponding CMS Block
                $this->blockActions->deleteCmsBlock($item->getData('url'));
            }

            $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $itemsSize));

            /** @var ResultInterface $result */
            $result = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        return $result->setPath('*/*');
    }
}
