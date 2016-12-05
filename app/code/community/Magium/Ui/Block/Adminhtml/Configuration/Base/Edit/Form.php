<?php

class Magium_Ui_Block_Adminhtml_Configuration_Base_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $store = $this->getRequest()->getParam('store', 0);
        $form = new Varien_Data_Form([
            'id' => 'edit_form',
            'action' => Mage::helper('adminhtml')->getUrl('*/*/saveBase', ['store' => $store]),
            'method' => 'post'
        ]);
        $form->setUseContainer(true);

        $fieldset = $form->addFieldset('general_form', array(
            'legend' => $this->__('Theme Selection')
        ));

        $themes = Mage::getModel('magium_ui/source_themes');

        $fieldset->addField('theme_configuration', 'select', array(
            'label' => $this->__('Theme Configuration'),
            'class' => 'required-entry',
            'required' => true,
            'options'   => $themes->toOptionArray(),
            'value' => $this->getValue('theme_configuration'),
            'name' => 'theme_configuration',
        ));

        $this->setForm($form);

        return parent::_prepareForm();
    }

    protected function getValue($value)
    {
        $collection = Mage::getModel('magium_ui/option')->getCollection();
        /* @var $collection Magium_Ui_Model_Resource_Option_Collection */
        $collection->setStoreId($this->getRequest()->getParam('store', 0));
        $collection->setNames([$value]);
        return $collection->getFirstItem()->getValue();
    }

}
