<?php

class Magium_Ui_Block_Adminhtml_Configuration_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('general');

        $fieldset = $form->addFieldset('general_form', array(
            'legend' => $this->__('Theme Configurations')
        ));

        $button = '<button type="button">Scan...</button>';

        $fieldset->addField('theme_search', 'textarea', array(
            'label' => $this->__('Theme Search Locations'),
            'required' => false,
            'rows'  => 3,
            'style' => 'height: auto;',
            'after_element_html' => '<small>One fully qualified directory per line.  Will search for any class extending Magium\Magento\Themes\AbstractThemeConfiguration</small>' . $button,
            'name' => 'theme_search',
        ));

        $themes = Mage::getModel('magium_ui/source_themes');

        $fieldset->addField('theme_configuration', 'select', array(
            'label' => $this->__('Theme Configuration'),
            'class' => 'required-entry',
            'required' => true,
            'options'   => $themes->toOptionArray(),
            'name' => 'theme_configuration',
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