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
            'title' => $this->__('Base Options'),
            'url'   => Mage::helper('adminhtml')->getUrl('adminhtml/magiumui_configuration/load', array('section' => 'base'))
        ));

        $config = Mage::getConfig()->getNode('magium/configuration/sections');

        foreach ($config->children() as $section => $node) {
            $label = (string)$node->label;
            $this->addTab($section, array(
                'label' => $this->__($label),
                'title' => $this->__($label),
                'url'   => Mage::helper('adminhtml')->getUrl('adminhtml/magiumui_configuration/section', array('section' => $section))
            ));
        }


        return parent::_beforeToHtml();
    }
}
