<?php

class Magium_Ui_Block_Adminhtml_Configuration_Index
    extends Mage_Adminhtml_Block_Widget_Form_Container
{

    protected function _construct()
    {
        parent::_construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'magium_ui';
        $this->_controller = 'adminhtml_configuration_index';
        $this->_mode = 'edit';
    }

    function getHeaderText()
    {
        return $this->__('Global Options');
    }
}
