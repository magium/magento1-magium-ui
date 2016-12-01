<?php

class Magium_Ui_Block_Adminhtml_Configuration_Section_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * @var Magium_Ui_Model_NullTestCase
     */
    protected $testCase;


    protected function _construct()
    {
        $this->testCase = Mage::getModel('magium_ui/nullTestCase');
        $initializer = Mage::getModel(
            'magium_ui/nullInitializer',
            Magium\TestCaseConfiguration::class
        );
        $initializer->initialize($this->testCase);
        $this->testCase->switchThemeConfiguration(Magium\Magento\Themes\Magento19\ThemeConfiguration::class);
        parent::_construct();
    }

    protected function _prepareForm()
    {
        $section = Mage::app()->getRequest()->getParam('section');
        $storeId = Mage::app()->getRequest()->getParam('store');
        $form = new Varien_Data_Form([
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save', ['section' => $section, 'store' => $storeId]),
            'method' => 'post'
        ]);
        $form->setUseContainer(true);

        $configuration = Mage::getConfig()->getNode('magium/configuration/sections/' . $section);
        $sourceType = (string)$configuration->type;
        $source = $this->testCase->get($sourceType);

        $elements = [];

        foreach ($configuration->groups->children() as $group) {
            $name = (string)$group->name;
            $fieldset = $form->addFieldset(preg_replace('/\W/', '', strtolower($name)), array(
                'legend' => $this->__($name)
            ));


            foreach ($group->elements->children() as $key => $child) {
                $elements[] = $key;
                $fieldset->addField($key, 'text', array(
                    'label' => $this->__((string)$child->label),
                    'name' => $key,
                    'value' => $this->getElementValue($source, $key)
                ));
            }

        }

        $collection = Mage::getModel('magium_ui/configuration')->getCollection();
        /* @var $collection Magium_Ui_Model_Resource_Configuration_Collection */
        $collection->setNames($elements);
        $collection->setStoreId(Mage::app()->getRequest()->getParam('store', 0));
        foreach ($collection as $model) {
            /* @var $model Magium_Ui_Model_Configuration */
            $form->getElement($model->getName())->setValue($model->getValue());
        }

        $this->setForm($form);

        return parent::_prepareForm();
    }

    protected function getElementValue(\Magium\AbstractConfigurableElement $source, $name)
    {
        $value = $source->$name;
        return $value;
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
