<?php

class Magium_Ui_Block_Adminhtml_Management_Form_Instruction extends Mage_Adminhtml_Block_Widget_Form
{
    protected $_containerIds = [];

    public function getContainerIds()
    {
        return $this->_containerIds;
    }

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $fieldset = $form->addFieldset('instruction', array(
            'legend' => $this->__('Instruction Type')
        ));

        $baseTypes = Mage::getModel('magium_ui/source_baseTypes');
        /* @var $baseTypes Magium_Ui_Model_Source_BaseTypes */

        $fieldset->addField('type', 'select', array(
            'label' => $this->__('Type'),
            'required' => false,
            'name' => 'type',
            'onchange'  => 'showSelectedItem()',
            'values' => $baseTypes->toOptionArray()
        ));

        $typeOptions = Mage::getModel('magium_ui/source_typeOptions');
        /* @var $typeOptions Magium_Ui_Model_Source_TypeOptions */

        foreach ($baseTypes->toOptionArray() as $child) {
            $type = $child['value'];
            $label = $child['label'];
            $typeOptions->setType($type);
            $id = preg_replace('/\W/', '', strtolower($label));
            $containerId = $id . '_container';
            $this->_containerIds[] = $containerId;
            $fieldset = $form->addFieldset($id, array(
                'legend' => $this->__($label),
                'fieldset_container_id'   => $containerId
            ));

            $fieldset->addField($id . '_instruction', 'select', [
                'label' => $this->__($label),
                'required' => true,
                'name' => $id,
                'values' => $typeOptions->toOptionArray(),
                'onchange'   => 'setRequiredParam()'
            ]);

            $fieldset->addField($id . '_instruction_param', 'text', [
                'label' => $this->__('Parameter'),
                'name' => $id . '_param'
            ]);

        }

        $this->setForm($form);

        return parent::_prepareForm();
    }


}
