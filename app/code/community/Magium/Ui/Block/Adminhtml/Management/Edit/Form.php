<?php

class Magium_Ui_Block_Adminhtml_Management_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset('pre_conditions_fieldset', array(
            'legend' => $this->__('Preconditions')
        ));

        $afterHtml = <<<HTML
<small style="margin-bottom: 20px;">Preconditions restrict test execution based off of variable conditions that you can provide.  For example, if a 
test is intended only for simple products you can add a precondition, one per line, such as this:</small>

<pre style="margin-bottom: 20px;">
{{\$product->getTypeId()}} == simple
{{\$product->isSaleable()}}
{{\$product->getQty()}} > 1
</pre>
<small>The value inside {{}} will be based on the available context.  Events, for example, may have the product, category,
or some other data available.  That data is injected into the Magium test prior to text execution and can be referenced 
here.  If <strong>any</strong> of the conditions specified evaluate to <span style="font-family: courier">false</span> 
the test will be skipped.  Quotes are not required for strings.  </small>
HTML;

        $fieldset->addField('pre_conditions', 'textarea', array(
            'label' => $this->__('Precondition'),
            'required' => false,
            'style'  => 'height: auto; ',
            'name' => 'pre_conditions',
            'after_element_html' => $afterHtml,
        ))->setRows(4);

        $fieldset = $form->addFieldset('general_form', array(
            'legend' => $this->__('Test Instructions')
        ));

        $fieldset->addField('command_open', 'text', array(
            'label' => $this->__('Command: Open'),
            'required' => true,
            'name' => 'command_open',
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
