<?php
declare(strict_types=1);

/**
 * K010-1: Uploads new image to the media gallery
 */

namespace HanStudio\EventPageBuilder\Controller\Adminhtml\Index;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action;
use HanStudio\EventPageBuilder\Model\ImageUploader;
use Magento\Backend\App\Action\Context;
use Magento\Framework\UrlInterface;

class Upload extends Action
{
    /**
     * @var ImageUploader
     */
    public $imageUploader;

    /**
     * @var ResultFactory
     */
    protected $resultFactory;

    /**
     * @var UrlInterface
     */
    protected $urlInterface;

    /**
     * Upload constructor.
     * @param Context $context
     * @param ImageUploader $imageUploader
     * @param ResultFactory $resultFactory
     * @param UrlInterface $urlInterface
     */
    public function __construct(
        Context       $context,
        ImageUploader $imageUploader,
        ResultFactory $resultFactory,
        UrlInterface  $urlInterface
    ) {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
        $this->resultFactory = $resultFactory;
        $this->urlInterface = $urlInterface;
    }

    /**
     * Checks if the user is allowed to access the event
     *
     * @return mixed
     */
    public function _isAllowed()
    {
        return $this->_authorization->isAllowed('HanStudio_EventPageBuilder::event');
    }

    /**
     * Uploads the image to the media gallery
     *
     * @return mixed
     */
    public function execute()
    {
//        $eventId = $this->getEventId();
        try {
            $result = $this->imageUploader->saveFileToTmpDir('image');
            $result['cookie'] = [
                'name' => $this->_getSession()->getName(),
                'value' => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path' => $this->_getSession()->getCookiePath(),
                'domain' => $this->_getSession()->getCookieDomain(),
            ];
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }

    /**
     * Get event ID from the URL
     *
     * @return string
     */
    private function getEventId(): string
    {
        // Extracting the event ID from the URL
        $refererUrl = $this->_redirect->getRefererUrl();
        // Define the regular expression pattern
        $pattern = '/\/eventid\/(\d+)/';
        // Use preg_match to find matches
        if (preg_match($pattern, $refererUrl, $matches)) {
            // The value is in $matches[1]
            $eventIdValue = $matches[1];
            return $eventIdValue;
        } else {
            return "0";
        }
    }
}
