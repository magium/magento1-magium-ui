<?php

class Magium_Clairvoyant_Block_Adminhtml_Management_Container
    extends Mage_Adminhtml_Block_Widget_Form_Container
{

    protected $_test;

    protected function _construct()
    {
        $this->setData('template', 'magium/widget/form/container.phtml');
        parent::_construct();

        $this->_blockGroup = 'magium_clairvoyant';
        $this->_controller = 'adminhtml_management';
    }

    public function setTest(Magium_Clairvoyant_Model_Test $test)
    {
        $this->_test = $test;
        $this->getChild('form')->setTest($test);
    }

    protected function _beforeToHtml()
    {

        $this->setData([
            'id' => 'edit_form',
            'method' => 'post',
            'enctype' => 'multipart/form-data'
        ]);
        $this->_updateButton('save', 'label', $this->__('Save Test'));

        $this->_addButton('execute_test', array(
            'label'     => Mage::helper('adminhtml')->__('Test Execution'),
            'class'     => 'save',
            'onclick'   => 'executeTest()'
        ), -100);

        $this->setAction($this->getUrl('*/*/saveTest',
            array('id' => $this->getRequest()->getParam('id'), 'store' => $this->getRequest()->getParam('store', 0))));

        $this->getLayout()->getBlock('magium_clairvoyant_instructions')->setTest($this->_test);

        return parent::_beforeToHtml();
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
