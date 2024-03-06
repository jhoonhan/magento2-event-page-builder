<?php

namespace HanStudio\EventPageBuilder\Controller\Adminhtml\Event;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;

class Get extends Action implements HttpGetActionInterface
{

    /**
     * Execute action based on request and return result
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData(["message" => ("Invalid Data"), "suceess" => true]);
        return $resultJson;
    }
}
