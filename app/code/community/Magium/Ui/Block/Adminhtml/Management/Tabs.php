<?php

class Magium_Ui_Block_Adminhtml_Configuration_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    protected function _construct()
    {
        parent::_construct();
        $this->setId('switch_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle($this->__('Magium Configuration'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('base_options', array(
            'label' => $this->__('Base Options'),
            'title' => $this->__('Base Options')
        ));

        $this->addTab('default_elements', array(
            'label' => $this->__('Store Defaults'),
            'title' => $this->__('Store Defaults')
        ));

        $this->addTab('admin_config', array(
            'label' => $this->__('Admin Configuration'),
            'title' => $this->__('Admin Configuration')
        ));

        $this->addTab('customer_config', array(
            'label' => $this->__('Customer Configuration'),
            'title' => $this->__('Customer Configuration')
        ));

        $this->addTab('navigation_configuration', array(
            'label' => $this->__('Navigation Configuration'),
            'title' => $this->__('Navigation Configuration')
        ));

        return parent::_beforeToHtml();
    }
}
