<?php

class Magium_Clairvoyant_Block_Adminhtml_Queue_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('magium_clairvoyant/queue_collection');
        if ($collection instanceof Magium_Clairvoyant_Model_Resource_Queue_Collection) {
            $collection->setOrder('created_at', 'DESC');
            $this->setCollection($collection);
        }
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('name', [
            'header' => $this->__('Test Name'),
            'index' => 'name'
        ]);
        $this->addColumn('event', [
            'header' => $this->__('Event'),
            'index' => 'event'
        ]);
        $this->addColumn('command_open', [
            'header' => $this->__('Open URL'),
            'index' => 'command_open',
            'renderer' => 'magium_clairvoyant/adminhtml_queue_renderer_url'
        ]);
        $this->addColumn('pre_conditions', [
            'header' => $this->__('Pre Conditions'),
            'index' => 'pre_conditions',
            'renderer'  => 'magium_clairvoyant/adminhtml_queue_renderer_preConditions'
        ]);
        $this->addColumn('actions', [
            'header' => $this->__('Actions'),
            'index' => 'actions_serialized',
            'renderer'  => 'magium_clairvoyant/adminhtml_queue_renderer_actions'
        ]);
        $this->addColumn('status', [
            'header' => $this->__('Status'),
            'index' => 'status',
            'type'  => 'options',
            'options'   => [
                Magium_Clairvoyant_Model_Queue::TEST_STATUS_PASSED    => 'Passed',
                Magium_Clairvoyant_Model_Queue::TEST_STATUS_FAILED    => 'Failed',
                Magium_Clairvoyant_Model_Queue::TEST_STATUS_SKIPPED    => 'Skipped',
                Magium_Clairvoyant_Model_Queue::TEST_STATUS_IN_PROCESS    => 'In Process',
                Magium_Clairvoyant_Model_Queue::TEST_STATUS_QUEUED    => 'Queued',
            ],
            'renderer'  => 'magium_clairvoyant/adminhtml_queue_renderer_status'
        ]);
        $this->addColumn('created_at', [
            'header' => $this->__('Created At'),
            'index' => 'created_at'
        ]);
        $this->addColumn('view_log', [
            'header' => $this->__('View Log'),
            'type'  => 'action',
            'index' => 'log',
            'renderer'  => 'magium_clairvoyant/adminhtml_queue_renderer_action',
        ]);
        return parent::_prepareColumns();
    }


}
