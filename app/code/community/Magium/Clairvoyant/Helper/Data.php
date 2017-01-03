<?php

class Magium_Clairvoyant_Helper_Data extends Mage_Core_Helper_Abstract
{
    protected $_testCase;

    public function getNullTestCase()
    {
        if (!$this->_testCase instanceof Magium_Clairvoyant_Model_NullTestCase) {
            $this->_testCase = Mage::getModel('magium_clairvoyant/nullTestCase');
            $initializer = Mage::getModel(
                'magium_clairvoyant/nullInitializer',
                Magium\TestCaseConfiguration::class
            );
            $initializer->initialize($this->_testCase);
        }
        return $this->_testCase;
    }

    /**
     * @return Magium_Clairvoyant_Model_Instruction_Test
     */

    public function getInstructionTestCase($bypassWebDriver = false)
    {
        $testCase = Mage::getModel('magium_clairvoyant/instruction_test');
        $initializer = Mage::getModel(
            'magium_clairvoyant/instruction_initializer',
            Magium\TestCaseConfiguration::class
        );
        if ($testCase instanceof Magium_Clairvoyant_Model_Instruction_Test) {
            $testCase->setName('testExecute');
            if ($initializer instanceof Magium_Clairvoyant_Model_Instruction_Initializer) {
                $initializer->bypassWebDriver($bypassWebDriver);
                $initializer->initialize($testCase);
                return $testCase;
            }
        }
        return null;
    }

}
