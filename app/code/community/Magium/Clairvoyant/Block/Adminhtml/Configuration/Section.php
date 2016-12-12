<?php

class Magium_Clairvoyant_Block_Adminhtml_Configuration_Section
    extends Mage_Adminhtml_Block_Widget_Form_Container
{

    protected function _construct()
    {
        parent::_construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'magium_clairvoyant';
        $this->_controller = 'adminhtml_configuration_section';
        $this->_mode = 'edit';
    }


    /**
     * Return the title string to show above the form
     *
     * @return string
     */
    public function getHeaderText()
    {
        $section = Mage::app()->getRequest()->getParam('section');
        $config = Mage::app()->getConfig()->getNode('magium/configuration/sections/' . $section);

        $storeId = Mage::app()->getRequest()->getParam('store');
        $storeName = 'All Stores';
        if ($storeId) {
            $storeName = Mage::app()->getStore($storeId)->getName();
        }

        return $this->__('%s in %s', (string)$config->label, $storeName);
    }
}
