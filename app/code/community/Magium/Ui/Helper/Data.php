<?php

class Magium_Ui_Helper_Data extends Mage_Core_Helper_Abstract
{
    protected $_testCase;

    public function getNullTestCase()
    {
        if (!$this->_testCase instanceof Magium_Ui_Model_NullTestCase) {
            $this->_testCase = Mage::getModel('magium_ui/nullTestCase');
            $initializer = Mage::getModel(
                'magium_ui/nullInitializer',
                Magium\TestCaseConfiguration::class
            );
            $initializer->initialize($this->_testCase);
        }
        return $this->_testCase;
    }

}
