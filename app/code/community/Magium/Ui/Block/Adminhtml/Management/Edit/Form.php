<?php

class Magium_Ui_Block_Adminhtml_Management_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('general');

        $fieldset = $form->addFieldset('general_form', array(
            'legend' => $this->__('Test Instructions')
        ));

        $fieldset->addField('manage_command', 'text', array(
            'label' => $this->__('Command: Open'),
            'value' => '{{product->getStoreUrl()}}',
            'required' => false,
            'name' => 'theme_search',
        ));

        $fieldset->addField('manage_assert_xpath', 'text', array(
            'label' => $this->__('Assert: Xpath\Exists'),
            'value' => '//h2[@class="product-name" .="{{product->getName()}}"]',
            'required' => false,
            'name' => 'theme_search',
        ));

        $fieldset->addField('manage_add_to_cart', 'text', array(
            'label' => $this->__('Execute: Cart\AddSimpleProductToCart'),
            'required' => false,
            'name' => 'theme_search',
        ));

        $fieldset->addField('add_instruction', 'button', array(
            'name'  => 'add_instruction',
            'value' => $this->__('Add Instruction'),
            'type' => 'button',
            'class' => 'save',
            'onclick' => 'test()'
        ));

        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     *
     * @return array
     */
    protected function _getFormData()
    {
        $data = [];

        return (array)$data;
    }
}