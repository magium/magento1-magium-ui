<?php

class Magium_Clairvoyant_Block_Adminhtml_Management_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    protected $_currentTest;

    protected function _construct()
    {
        parent::_construct();
        $this->setId('switch_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle($this->__('Magium Tests'));
    }

    public function setTest(Magium_Clairvoyant_Model_Test $testId)
    {
        $this->_currentTest = $testId;
    }

    protected function _beforeToHtml()
    {
        $this->addTab('new_test', array(
            'label' => $this->__('Create New Test'),
            'title' => $this->__('Create New Test'),
            'url'   => Mage::helper('adminhtml')->getUrl('*/*/index'),
        ));

        $testCollection = Mage::getModel('magium_clairvoyant/test')->getCollection();
        /* @var $testCollection Magium_Clairvoyant_Model_Resource_Test_Collection */
        $testCollection->addFieldToFilter('store_id', $this->getRequest()->getParam('store', 0));
        foreach ($testCollection as $test) {
            /* @var $test Magium_Clairvoyant_Model_Test */
            $this->addTab('test_' . $test->getId(),[
                'label' => $this->__($test->getName()),
                'title' => $this->__($test->getName()),
                'url'   => Mage::helper('adminhtml')->getUrl('*/*/edit', ['id' => $test->getId()]),
            ]);
            if ($this->_currentTest instanceof Magium_Clairvoyant_Model_Test && $test->getId() == $this->_currentTest->getId()) {
                $this->setActiveTab('test_' . $this->_currentTest->getId());
            }
        }

        return parent::_beforeToHtml();
    }
}
