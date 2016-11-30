<?php

class Magium_Ui_Block_Configuration_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    protected function _construct()
    {
        parent::_construct();
        $this->setId('switch_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle($this->__('General Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('general_section', array(
            'label' => $this->__('General'),
            'title' => $this->__('General'),
            'content' => $this->getLayout()
                ->createBlock('magium_ui/adminhtml_configuration_form')
                ->toHtml(),
        ));
        return parent::_beforeToHtml();
    }
}
