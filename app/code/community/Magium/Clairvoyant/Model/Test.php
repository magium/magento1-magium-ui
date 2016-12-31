<?php

/**
 * Class Magium_Clairvoyant_Model_Test
 *
 * @method setCommandOpen($url)
 * @method setName($name)
 * @method setPreConditions($conditions)
 * @method setStoreId($storeid)
 * @method string getCommandOpen()
 * @method string getName()
 * @method string getPreConditions()
 * @method string getStoreId()
 */

class Magium_Clairvoyant_Model_Test extends Mage_Core_Model_Abstract
{

    /**
     * @var Magium_Clairvoyant_Model_Resource_Instruction_Collection
     */

    protected $_instructions;

    protected $_items = [];

    protected function _construct()
    {
        $this->_init('magium_clairvoyant/test');
    }

    public function addInstruction(Magium_Clairvoyant_Model_Instruction $instruction)
    {
        $this->_items[] = $instruction;
    }

    protected function _afterSave()
    {
        if ($this->_items) {
            $resource = Mage::getModel('magium_clairvoyant/instruction')->getResource();
            /* @var $resource Magium_Clairvoyant_Model_Resource_Instruction */
            $resource->deleteTestInstructions($this);
        }
        foreach ($this->_items as $item) {
            if ($item instanceof Magium_Clairvoyant_Model_Instruction) {
                $item->setTestId($this->getId());
                $item->save();
            }
        }
        return parent::_afterSave();
    }

    /**
     * @return Magium_Clairvoyant_Model_Instruction[]
     */

    public function getInstructions()
    {
        return $this->getInstructionsCollection()->getItems();
    }

    /**
     *
     * @return Magium_Clairvoyant_Model_Resource_Instruction_Collection
     */

    public function getInstructionsCollection()
    {
        if (!$this->_instructions instanceof Magium_Clairvoyant_Model_Resource_Instruction_Collection) {
            $this->_instructions = Mage::getModel('magium_clairvoyant/instruction')->getCollection();

            $this->_instructions->addFieldToFilter('test_id', $this->getId());
        }
        return $this->_instructions;
    }

}
