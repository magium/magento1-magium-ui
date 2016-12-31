<?php

class Magium_Clairvoyant_Block_Adminhtml_Configuration_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $defaultTab = null;
        if (!$this->getRequest()->getParam('store')) {
            $this->addTab('global_options', array(
                'label' => $this->__('Global Options'),
                'title' => $this->__('Global Options'),
                'url' => Mage::helper('adminhtml')->getUrl('adminhtml/magiumui_configuration/index')
            ));
            $defaultTab = 'global_options';
        }

        $this->addTab('base_options', array(
            'label' => $this->__('Base Options'),
            'title' => $this->__('Base Options'),
            'url' => Mage::helper('adminhtml')->getUrl('adminhtml/magiumui_configuration/base')
        ));
        if (!$defaultTab) $defaultTab = 'base_options';

        $config = Mage::getConfig()->getNode('magium/configuration/sections');
        $store = $this->getRequest()->getParam('store');
        foreach ($config->children() as $section => $node) {
            $label = (string)$node->label;
            $params = array('section' => $section);
            if ($store) {
                $params['store'] = $store;
            }
            $this->addTab($section, array(
                'label' => $this->__($label),
                'title' => $this->__($label),
                'url'   => Mage::helper('adminhtml')->getUrl('adminhtml/magiumui_configuration/section', $params)
            ));
        }

        if($this->getRequest()->getActionName() == 'base') {
            $this->setActiveTab('base_options');
        } else {
            $this->setActiveTab($this->getRequest()->getParam('section', $defaultTab));
        }

        return parent::_beforeToHtml();
    }

}
