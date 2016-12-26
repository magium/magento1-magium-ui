<?php

class Magium_Clairvoyant_Model_Observer
{

    protected $_introspected = [];

    public function observe(Varien_Event_Observer $observer)
    {
        $testCollection = Mage::getModel('magium_clairvoyant/test')->getCollection();
        if ($testCollection instanceof Magium_Clairvoyant_Model_Resource_Test_Collection) {
            $testCollection->join(
                ['mce' => 'magium_clairvoyant/event'],
                'mce.test_id = main_table.entity_id',
                null
            );
            $testCollection->addFieldToFilter('mce.event', $observer->getEvent()->getName());
            $testCollection->addFieldToFilter('mce.store_id', ['in' => [0, $this->_introspectStoreId($observer)]]);
            $testCollection->getSelect()->group('entity_id');
            foreach ($testCollection as $test) {
                $this->_executeTest($observer, $test);
            }
        }
    }

    protected function _introspectStoreId(Varien_Event_Observer $observer)
    {
        $storeId = 0;
        foreach ($observer->getData() as $object) {
            if ($object instanceof Varien_Object) {
                $testStoreId = $object->getStoreId();
                if ($testStoreId && $testStoreId > $storeId) {
                    return $testStoreId;
                }
            }
        }
        return 0;
    }

    protected function _getMethod(Magium_Clairvoyant_Model_Instruction $instruction)
    {
        if (!$this->_introspected) {
            $introspected = Mage::getModel('magium_clairvoyant/introspected')->getCollection();
            foreach ($introspected as $item) {
                if ($item instanceof Magium_Clairvoyant_Model_Introspected) {
                    $this->_introspected[$item->getClass()] = $item;
                }
            }
        }

        $class = $instruction->getClass();
        if (!isset($this->_introspected[$class])) {
            Mage::throwException('Missing introspection definition for ' . $class);
        }

        $reflection = new ReflectionClass($this->_introspected[$class]->getFunctionalType());
        $methods = $reflection->getMethods();
        if ($methods) {
            $method = $methods[0];
            return $method->getName();
        }
        Mage::throwException('Unable to determine method for ' . $class);
    }

    protected function _executeTest(Varien_Event_Observer $event, Magium_Clairvoyant_Model_Test $test)
    {
        $helper = Mage::helper('magium_clairvoyant');
        if ($helper instanceof Magium_Clairvoyant_Helper_Data) {
            $testInstance = $helper->getInstructionTestCase();
            $testInstance->setName('testExecute');
            $di = $testInstance->getDi();
            foreach ($event->getData() as $key => $data) {
                $di->instanceManager()->addSharedInstance($data, $key);
                if (is_object($data)) {
                    $di->instanceManager()->addAlias($key, get_class($data));
                }
            }

            $instructions = $test->getInstructions();
            $instructionCollection = $testInstance->get(\Magium\TestCase\Configurable\InstructionsCollection::class);
            $interpolator = $testInstance->get(\Magium\TestCase\Configurable\Interpolator::class);
            if ($interpolator instanceof \Magium\TestCase\Configurable\Interpolator
                && $instructionCollection instanceof \Magium\TestCase\Configurable\InstructionsCollection
            ) {
                foreach ($instructions as $instruction) {
                    $param = $instruction->getParam();
                    $param = $interpolator->interpolate($param);
                    $method = $this->_getMethod($instruction);
                    $genericInstruction = new \Magium\TestCase\Configurable\GenericInstruction(
                        $instruction->getClass(),
                        $method,
                        $param
                    );
                    $instructionCollection->addInstruction($genericInstruction);

                }
                $url = $interpolator->interpolate($test->getCommandOpen());
                $testInstance->setBaseUrl($url);
                $testInstance->setInstructions($instructionCollection);
                $result = $testInstance->run();
                $passed = $result->passed();
                if (count($passed) == 1) {
                    Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('magium_clairvoyant')->__(
                            'Magium test "%s" passed',
                            $test->getName()
                        )
                    );
                } else {
                    Mage::getSingleton('adminhtml/session')->addError(
                        Mage::helper('magium_clairvoyant')->__(
                            'Magium test %s failed',
                            $test->getName()
                        )
                    );
                }

            }

        }
    }

}
