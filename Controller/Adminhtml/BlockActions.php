<?php declare(strict_types=1);
/**
 * Created by Joe Han
 * E010 - Creation and management of CMS blocks triggered by Event/Save controller
 */

namespace HanStudio\EventPageBuilder\Controller\Adminhtml;

use Laminas\Stdlib\ParametersInterface;

use HanStudio\EventPageBuilder\Model\Event;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Cms\Model\BlockFactory;
use HanStudio\EventPageBuilder\Block\Adminhtml\BlockTemplate;
use Magento\Cms\Api\BlockRepositoryInterface;

class BlockActions extends Action
{

    /**
     * BlockActions constructor.
     * @param Context $context
     * @param BlockFactory $blockFactory
     * @param BlockTemplate $blockTemplate
     * @param BlockRepositoryInterface $blockRepository
     */
    public function __construct(
        private Context                  $context,
        private BlockFactory             $blockFactory,
        private BlockTemplate            $blockTemplate,
        private BlockRepositoryInterface $blockRepository
    ) {
        parent::__construct($context);
    }

    /**
     * Check if the block exists
     *
     * @param string $url
     * @return bool
     */
    public function isExistingBlock(string $url): bool
    {
        if (!$url) {
            return false;
        }
        try {
            if ($this->blockRepository->getById($url)) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Retrieve the title of the block
     *
     * @param string $block_id
     * @return string
     */
    public function getTitle(string $block_id): string
    {
        try {
            $isExistingBlock = $this->blockRepository->getById($block_id);
            return $isExistingBlock->getTitle();
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $e->getMessage();
        }
    }

    /**
     * Update or create a CMS block
     *
     * @param Event $event
     * @param ParametersInterface|array $post
     * @return void
     */
    public function updateCmsBlock(Event $event, ParametersInterface|array $post): void
    {
        try {
            $orgUrl = $event->getData('url');
            $postUrl = $post['url'];
            $event_id = $event->getData('event_id');

            //            New event or Edit event but no block
            if (!$orgUrl || !$this->isExistingBlock($orgUrl)) {
                //            Create with new url
                $this->blockTemplate->setData('event_id', $event_id);
                $htmlContent = $this->blockTemplate->getHtmlContent();

                $newBlockValues = [
                    'title' => $post['name'],
                    'identifier' => $postUrl,
                    'stores' => [0],
                    'is_active' => 1,
                    'content' => $htmlContent
                ];
                $this->blockFactory->create()->setData($newBlockValues)->save();
            }

            //            Edit event and existing block
            if ($orgUrl && $this->isExistingBlock($orgUrl)) {
                $updateBlock = $this->blockFactory->create()->load(
                    $orgUrl,
                    'identifier'
                );
                $updateBlockValues = [
                    'identifier' => $postUrl,
                    'title' => $post['name'],
                    'stores' => [0],
                ];
                $updateBlock->setData(array_merge($updateBlock->getData(), $updateBlockValues))->save();
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
    }

    /**
     * E010-1: Delete CMS Block
     *
     * @param string $url
     * @return void
     */
    public function deleteCmsBlock(string $url): void
    {
        try {
            $targetBlock = $this->blockFactory->create()->load($url);
            $targetBlock->delete();
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
    }

    /**
     * Execute
     *
     * @return void
     */
    public function execute()
    {
        parent::__construct($this->context);
    }
}
