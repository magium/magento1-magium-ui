<?php

class Magium_Clairvoyant_Block_Adminhtml_Execution_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('magium_clairvoyant/test_collection');
        if ($collection instanceof Magium_Clairvoyant_Model_Resource_Test_Collection) {
            $adapter = $collection->getResource()->getReadConnection();
            $joinWhere = $adapter->select();
            $joinWhere->where('mce.test_id = main_table.entity_id');
            $joinWhere->where('mce.store_id = ?', $this->getRequest()->getParam('store', 0));
            $joinWhere->where('mce.event = ?', $this->getRequest()->getParam('event'));
            $select = $collection->getSelect();
            $select->joinLeft(
                ['mce' => $collection->getTable('magium_clairvoyant/event')],
                implode(' ', $joinWhere->getPart(Zend_Db_Select::WHERE)),
                [
                    new Zend_Db_Expr("case when  mce.entity_id > 0 then 'assigned' else 'unassigned' end as assigned"),
                    'mce.entity_id as event_id'
                ]
            );
            $this->setCollection($collection);
        }
        return parent::_prepareCollection();
    }

    public function shouldRenderCell($item, $column)
    {
        if ($column->getId() == 'action' && $column->getType() == 'action') {
            $title = 'Attach';
            $route = 'assign';
            if ($item->getAssigned() == 'assigned') {
                $title = 'Detach';
                $route = 'unassign';
            }
            $action = [
                'caption' => Mage::helper('magium_clairvoyant')->__($title),
                'url' => [
                    'base' => '*/*/' . $route,
                    'params' => [
                        'store' => $this->getRequest()->getParam('store'),
                        'event' => $this->getRequest()->getParam('event'),
                        'item' => $item->getEventId()
                    ]
                ],
                'field' => 'id'
            ];

            $column->setActions([$action]);
        }
        return parent::shouldRenderCell($item, $column);
    }

    protected function _prepareColumns()
    {
        $this->addColumn('test', [
            'header' => $this->__('Test'),
            'index' => 'name',
        ]);
        $this->addColumn('assigned', [
            'header' => $this->__('Assigned'),
            'index' => 'assigned',
            'width' => '200px',
        ]);
        $this->addColumn('action', [
            'header' => $this->__('Action'),
            'index' => 'action',
            'type' => 'action',
            'width' => '100px',
            'getter' => 'getId'
        ]);
        return parent::_prepareColumns();
    }

}
