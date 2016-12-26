<?php

/**
 * Class Magium_Clairvoyant_Model_Event
 *
 * @method Magium_Clairvoyant_Model_Event setStoreId($storeId)
 * @method Magium_Clairvoyant_Model_Event setTestId($testId)
 * @method Magium_Clairvoyant_Model_Event setEvent($event)
 * @method int getStoreId()
 * @method int getTestId()
 * @method string getEvent()
 */

class Magium_Clairvoyant_Model_Event extends Mage_Core_Model_Abstract
{

    protected $_errors;

    protected function _construct()
    {
        $this->_init('magium_clairvoyant/event');
    }

    /**
     * @return array
     */

    public function getErrors()
    {
        return $this->_errors;
    }

    public function validate()
    {
        $errors = [];
        if (!Zend_Validate::is($this->getStoreId(), 'Int')) {
            $errors[] = Mage::helper('magium_clairvoyant')->__('Missing store');
        }
        if (!Zend_Validate::is($this->getTestId(), 'Int')) {
            $errors[] = Mage::helper('magium_clairvoyant')->__('Missing test');
        } else {
            $test = Mage::getModel('magium_clairvoyant/test')->load($this->getTestId());
            if (!$test->getId()) {
                $errors[] = Mage::helper('magium_clairvoyant')->__('Invalid test');
            }
        }
        if (!Zend_Validate::is($this->getEvent(), 'NotEmpty')) {
            $errors[] = Mage::helper('magium_clairvoyant')->__('Missing event');
        } else {
            $configPath = 'magium/execution/' . $this->getEvent();
            if (!Mage::app()->getConfig()->getNode($configPath)) {
                $errors[] = Mage::helper('magium_clairvoyant')->__('Invalid event');
            }
        }
        if (count($errors)) {
            $this->_errors = $errors;
            return false;
        }
        return true;
    }
}
