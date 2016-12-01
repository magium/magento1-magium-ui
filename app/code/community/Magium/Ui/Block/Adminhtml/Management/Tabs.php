<?php

class Magium_Ui_Block_Adminhtml_Management_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    protected function _construct()
    {
        parent::_construct();
        $this->setId('switch_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle($this->__('Magium Tests'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('new_test', array(
            'label' => $this->__('Create New Test'),
            'title' => $this->__('Create New Test')
        ));

        $this->addTab('default_elements', array(
            'label' => $this->__('Validate Category'),
            'title' => $this->__('Validate Category')
        ));

        $this->addTab('admin_config', array(
            'label' => $this->__('Ensure Homepage Links Exist'),
            'title' => $this->__('Ensure Homepage Links Exist')
        ));

        return parent::_beforeToHtml();
    }
}
