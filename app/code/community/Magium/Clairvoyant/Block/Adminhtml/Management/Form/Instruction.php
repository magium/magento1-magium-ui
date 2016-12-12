<?php

class Magium_Clairvoyant_Block_Adminhtml_Management_Form_Instruction extends Mage_Adminhtml_Block_Widget_Form
{
    protected $_containerIds = [];


    /**
     * @var Magium_Clairvoyant_Model_Test
     */
    protected $_test;

    public function setTest(Magium_Clairvoyant_Model_Test $test)
    {
        $this->_test = $test;
    }

    public function getContainerIds()
    {
        return $this->_containerIds;
    }


    public function getInstructionsAsArray()
    {
        $instructions = [];

        if ($this->_test instanceof Magium_Clairvoyant_Model_Test) {
            foreach ($this->_test->getInstructions() as $instruction) {
                $instructions[] = [
                    'type'  => $instruction->getType(),
                    'class'  => $instruction->getClass(),
                    'param'  => $instruction->getParam(),
                ];
            }
        }


        return $instructions;
    }

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $fieldset = $form->addFieldset('instruction', array(
            'legend' => $this->__('Instruction Type')
        ));

        $baseTypes = Mage::getModel('magium_clairvoyant/source_baseTypes');
        /* @var $baseTypes Magium_Clairvoyant_Model_Source_BaseTypes */

        $fieldset->addField('type', 'select', array(
            'label' => $this->__('Type'),
            'required' => false,
            'name' => 'type',
            'onchange'  => 'showSelectedItem()',
            'values' => $baseTypes->toOptionArray()
        ));

        $typeOptions = Mage::getModel('magium_clairvoyant/source_typeOptions');
        /* @var $typeOptions Magium_Clairvoyant_Model_Source_TypeOptions */

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
                'onchange'   => 'ensureParameterProperlySet()'
            ]);

            $fieldset->addField($id . '_instruction_param', 'text', [
                'label' => $this->__('Parameter'),
                'required'  => true,
                'name' => $id . '_param'
            ]);

        }

        $this->setForm($form);

        return parent::_prepareForm();
    }


}
