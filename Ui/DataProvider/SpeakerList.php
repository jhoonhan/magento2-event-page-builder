<?php declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Ui\DataProvider;

use HanStudio\EventPageBuilder\Model\ResourceModel\Speaker\CollectionFactory;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider;

class SpeakerList extends DataProvider
{

//    Event's id is retracted from URL param and gets compared to speaker's event_id.

    /**
     * Filters speakers based on their event_id.
     *
     * @return void
     */
    public function prepareUpdateUrl()
    {
        parent::prepareUpdateUrl();
        if ($this->request->getParam('event_id')) {
            $event_id = $this->request->getParam('event_id');
        } else {
            $event_id = null;
        }

        if ($event_id) {
            $this->addFilter(
                $this->filterBuilder->setField('event_id')
                    ->setValue($event_id)
                    ->setConditionType('eq')
                    ->create()
            );
        }
    }
}
