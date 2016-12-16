<?php

class Magium_Clairvoyant_Block_Adminhtml_Management_Builder extends Mage_Core_Block_Template
{

    const TYPE_PARAM_NONE = 'none';
    const TYPE_PARAM_OPTIONAL = 'optional';
    const TYPE_PARAM_REQUIRED = 'required';

    protected $_functionalTypeRequirements;

    protected $_form;

    protected $_requirements = [
        self::TYPE_PARAM_REQUIRED => [
            \Magium\Actions\ConfigurableActionInterface::class,
            \Magium\Assertions\SelectorAssertionInterface::class,
            \Magium\Navigators\ConfigurableNavigatorInterface::class,
        ],
        self::TYPE_PARAM_OPTIONAL => [
            \Magium\Actions\OptionallyConfigurableActionInterface::class,
            \Magium\Navigators\OptionallyConfigurableNavigatorInterface::class
        ]
    ];

    protected $_test;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('magium/management.phtml');
    }

    public function setTest(Magium_Clairvoyant_Model_Test $test)
    {
        $this->_test = $test;
        $this->getForm()->setTest($this->_test);
    }

    /**
     *
     * @var Magium_Clairvoyant_Block_Adminhtml_Management_Form_Instruction
     */

    public function getForm()
    {
        if (!$this->_form instanceof Magium_Clairvoyant_Block_Adminhtml_Management_Form_Instruction) {
            $this->_form = Mage::getBlockSingleton('magium_clairvoyant/adminhtml_management_form_instruction');

        }
        if ($this->_test instanceof Magium_Clairvoyant_Model_Test) {
            $this->_form->setTest($this->_test);
        }
        return $this->_form;
    }

    public function getHiddenContainerIds()
    {
        $block = $this->getForm();
        if ($block instanceof Magium_Clairvoyant_Block_Adminhtml_Management_Form_Instruction) {
            return $block->getContainerIds();
        }
        return [];
    }

    public function typeParamRequirements()
    {
        if (!$this->_functionalTypeRequirements) {
            $introspected = Mage::getModel('magium_clairvoyant/introspected')->getCollection();

            /* @var $introspected Magium_Clairvoyant_Model_Resource_Introspected_Collection */

            foreach ($introspected as $item) {
                /* @var $item Magium_Clairvoyant_Model_Introspected */

                $requirement = self::TYPE_PARAM_NONE;
                $class = $item->getFunctionalType();

                foreach ($this->_requirements as $type => $options) {
                    if (in_array($class, $options)) {
                        $requirement = $type;
                        break;
                    }
                }
                $this->_functionalTypeRequirements[$item->getClass()] = $requirement;
            }
        }

        return $this->_functionalTypeRequirements;

    }


}
