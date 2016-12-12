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

}
