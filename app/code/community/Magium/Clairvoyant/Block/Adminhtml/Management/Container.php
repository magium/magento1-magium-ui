<?php

class Magium_Clairvoyant_Block_Adminhtml_Management_Container
    extends Mage_Adminhtml_Block_Widget_Form_Container
{

    protected $_test;

    protected function _construct()
    {
        parent::_construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'magium_clairvoyant';
        $this->_controller = 'adminhtml_management';
        $this->_mode = 'edit';
    }

    public function setTest(Magium_Clairvoyant_Model_Test $test)
    {
        $this->_test = $test;
        $this->getChild('form')->setTest($test);
    }

    protected function  _prepareLayout()
    {

        $this->_updateButton('save', 'label', $this->__('Save Test'));

        $this->_addButton('execute_test', array(
            'label' => Mage::helper('adminhtml')->__('Execute Test'),
            'class' => 'save',
        ), -100);


        return parent::_prepareLayout();
    }

    /**
     * Return the title string to show above the form
     *
     * @return string
     */
    public function getHeaderText()
    {

        if ($this->_test && $this->_test->getId()) {
            return $this->__('Edit Test');
        } else {
            return $this->__('New Test');
        }
    }
}
