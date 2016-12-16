<?php

class Magium_Clairvoyant_Block_Adminhtml_Management_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected $_test;

    public function setTest(Magium_Clairvoyant_Model_Test $test)
    {
        $this->_test = $test;
    }

    protected function _getTestValue($param)
    {
        if ($this->_test instanceof Magium_Clairvoyant_Model_Test) {
            return $this->_test->getData($param);
        }
        return '';
    }

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setUseContainer(false);

        $fieldset = $form->addFieldset('header_fieldset', array(
            'legend' => $this->__('Setup')
        ));

        $fieldset->addField('test_name', 'text', array(
            'label' => $this->__('Test Name'),
            'required' => true,
            'style'  => 'height: auto; ',
            'name' => 'test_name',
            'value' => $this->_getTestValue('name')
        ));

        $afterHtml = <<<HTML
<button type="button" id="show-instructions">Show Instructions..</button>
<div id="instructions" style="display: none;">
<small style="margin-bottom: 20px;">Preconditions restrict test execution based off of variable conditions that you can provide.  For example, if a 
test is intended only for simple products you can add a precondition, one per line, such as this:</small>

<pre style="margin-bottom: 20px;">
{{\$product->getTypeId()}} == simple
{{\$product->isSaleable()}}
{{\$product->getQty()}} > 1
</pre>
<small>The value inside {{}} will be based on the available context.  Events, for example, may have the product, category,
or some other data available.  That data is injected into the Magium test prior to text execution and can be referenced 
here.  If <strong>any</strong> of the conditions specified evaluate to <span style="font-family: 'Courier New', monospace;">false</span> 
the test will be skipped.  Quotes are not required for strings.  </small>
<button type="button" class="back" id="close-instructions" style="margin-top: 20px;">Close Instructions</button>
</div>
<script type="text/javascript">
$('show-instructions').observe('click', toggleInstructions);
$('close-instructions').observe('click', toggleInstructions);

function toggleInstructions() {
   $('show-instructions').toggle();  
   $('instructions').toggle();  
}
</script>
HTML;

        $fieldset->addField('pre_conditions', 'textarea', array(
            'label' => $this->__('Preconditions'),
            'required' => false,
            'style'  => 'height: auto; ',
            'name' => 'pre_conditions',
            'after_element_html' => $afterHtml,
            'value' => $this->_getTestValue('pre_conditions')
        ))->setRows(4);

        $fieldset = $form->addFieldset('form_instructions', array(
            'legend' => $this->__('Test Instructions')
        ));

        $fieldset->addField('command_open', 'text', array(
            'label' => $this->__('Command: Open'),
            'required' => true,
            'name' => 'command_open',
            'value' => $this->_getTestValue('command_open')
        ));

        $this->setForm($form);

        return parent::_prepareForm();
    }

}
