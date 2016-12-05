<?php

class Magium_Ui_Block_Adminhtml_Configuration_Index_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form([
            'id' => 'edit_form',
            'action' => Mage::helper('adminhtml')->getUrl('*/*/saveGlobal'),
            'method' => 'post'
        ]);
        $form->setUseContainer(true);

        $fieldset = $form->addFieldset('additional_classes', array(
            'legend' => $this->__('Additional (Your) Classes')
        ));
        $scanUrl = Mage::helper('adminhtml')->getUrl('*/*/scan');
        $thisUrl = Mage::helper('adminhtml')->getUrl('*/*/*');

        $button = <<<HTML
<button type="button" id="scan_tests" onclick="scanTests()">Scan...</button>
<script type="text/javascript">

function scanTests() {
    var paths = $('class_search').getValue();
    new Ajax.Request(
        '{$scanUrl}', {
            method:'POST',
            parameters: {paths: paths},
            requestHeaders: {Accept: 'application/json'},
            onSuccess:function(transport){
                var response=transport.responseText.evalJSON(true);
                if (response.success) {
                    window.location.href='{$thisUrl}';
                }
            }
        }
    );
    return false;
}

</script> 
HTML;

        $fieldset->addField('class_search', 'textarea', array(
            'label' => $this->__('Class Search Locations'),
            'required' => false,
            'rows'  => 3,
            'style' => 'height: auto;',
            'after_element_html' => '<small>One fully qualified directory per line.  Make sure you save before scanning.</small><Br>' . $button,
            'value' => $this->getValue('class_search'),
            'name' => 'class_search',
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
