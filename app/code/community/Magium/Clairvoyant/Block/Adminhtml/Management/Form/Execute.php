<?php

class Magium_Clairvoyant_Block_Adminhtml_Management_Form_Execute extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setUseContainer(false);
        $fieldset = $form->addFieldset('execution_injection', array(
            'legend' => $this->__('Add Test Injections')
        ));

        $fieldset->addField('execution_identifier', 'text', array(
            'label' => $this->__('Injection Identifier (i.e. product) '),
            'required' => false,
            'name' => 'execution_identifier',
        ));
        $fieldset->addField('execution_model', 'text', array(
            'label' => $this->__('Model Name (i.e. catalog/product)'),
            'required' => false,
            'name' => 'execution_model',
        ));
        $fieldset->addField('execution_primary_key', 'text', array(
            'label' => $this->__('Model Primary Key (i.e. 12345)'),
            'required' => false,
            'name' => 'execution_primary_key',
        ));

        $form->addFieldset('current_execution_injections', array(
            'legend' => $this->__('Current Injections')
        ));

        $this->setForm($form);

        return parent::_prepareForm();
    }


}
