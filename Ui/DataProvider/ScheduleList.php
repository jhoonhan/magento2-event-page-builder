<?php declare(strict_types=1);

namespace HanStudio\EventPageBuilder\Ui\DataProvider;

use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider;

class ScheduleList extends DataProvider
{

    /**
     * K006: Filters schedules based on their event_id.
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
