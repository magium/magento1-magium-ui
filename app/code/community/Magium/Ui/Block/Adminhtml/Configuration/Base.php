<?php

class Magium_Ui_Block_Adminhtml_Configuration_Base
    extends Mage_Adminhtml_Block_Widget_Form_Container
{

    protected function _construct()
    {
        parent::_construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'magium_ui';
        $this->_controller = 'adminhtml_configuration_base';
        $this->_mode = 'edit';
    }

    /**
     * Return the title string to show above the form
     *
     * @return string
     */
    public function getHeaderText()
    {
        $store = 'All Store Views';
        $storeId = $this->getRequest()->getParam('store');
        if ($storeId) {
            $store = Mage::app()->getStore()->getName();
        }
        return $this->__('Manage Configuration for %s', $store);
    }
}
