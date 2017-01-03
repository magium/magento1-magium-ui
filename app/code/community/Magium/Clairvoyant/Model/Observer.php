<?php

class Magium_Clairvoyant_Model_Observer
{

    const CONFIG_QUEUE_EXECUTION = 'magium/general/use_queue';
    const CONFIG_QUEUE_THROW_EXCEPTION = 'magium/general/throw_exception';

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
            $testInstance = $helper->getInstructionTestCase(
                Mage::getStoreConfigFlag(self::CONFIG_QUEUE_EXECUTION)
            );

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
            $interpolatedInstructions = [];
            if ($interpolator instanceof \Magium\TestCase\Configurable\Interpolator
                && $instructionCollection instanceof \Magium\TestCase\Configurable\InstructionsCollection
            ) {
                foreach ($instructions as $instruction) {
                    $class = $instruction->getClass();
                    $param = $instruction->getParam();
                    $param = $interpolator->interpolate($param);
                    $method = $this->_getMethod($instruction);
                    $genericInstruction = new \Magium\TestCase\Configurable\GenericInstruction(
                        $class,
                        $method,
                        $param
                    );
                    $interpolatedInstructions[] = $genericInstruction;
                    $instructionCollection->addInstruction($genericInstruction);

                }
                $url = $interpolator->interpolate($test->getCommandOpen());
                $testInstance->setBaseUrl($url);
                $preConditions = explode("\n", $test->getPreConditions());
                $injectConditions = [];
                foreach ($preConditions as $condition) {
                    $condition = $interpolator->interpolate(trim($condition));
                    $injectConditions[] = $condition;
                }
                $test->setPreconditions($injectConditions);
                $testInstance->setPreconditions($injectConditions);
                $testInstance->setInstructions($instructionCollection);

                $queue = Mage::getModel('magium_clairvoyant/queue');
                if ($queue instanceof Magium_Clairvoyant_Model_Queue){
                    $queue->setEvent($event->getEvent()->getName());
                    $queue->setName($test->getName());
                    $queue->setPreConditions(serialize($injectConditions));
                    $queue->setCommandOpen($url);
                    $queue->setTestId($test->getId());
                    $queue->setActionsSerialized(serialize($interpolatedInstructions));
                    $queue->setCreatedAt(Varien_Date::now());
                    $queue->setStatus(Magium_Clairvoyant_Model_Queue::TEST_STATUS_QUEUED);
                } else {
                    return;
                }

                if (Mage::getStoreConfigFlag(self::CONFIG_QUEUE_EXECUTION)) {
                    Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('magium_clairvoyant')->__(
                            'Magium test "%s" queued',
                            $test->getName()
                        )
                    );
                    $queue->save();
                    return;
                }

                $logger = $testInstance->getLogger();
                $writer = Mage::getModel('magium_clairvoyant/logger_events');
                if ($writer instanceof Magium_Clairvoyant_Model_Logger_Events) {
                    $logger->addWriter($writer);
                } else {
                    return;
                }

                $queue->setExecutedAt(Varien_Date::now());
                $queue->save();

                $result = $testInstance->run();

                $queue->setLog(serialize($writer->getEvents()));
                $queue->setCompletedAt(Varien_Date::now());

                $passed = $result->passed();
                $skipped = $result->skipped();

                if (count($passed) == 1) {
                    $queue->setStatus(Magium_Clairvoyant_Model_Queue::TEST_STATUS_PASSED);
                    Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('magium_clairvoyant')->__(
                            'Magium test "%s" passed',
                            $test->getName()
                        )
                    );
                } else if (count($skipped) == 1) {
                    $queue->setStatus(Magium_Clairvoyant_Model_Queue::TEST_STATUS_SKIPPED);
                    Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('magium_clairvoyant')->__(
                            'Magium test "%s" skipped',
                            $test->getName()
                        )
                    );
                } else {
                    $queue->setStatus(Magium_Clairvoyant_Model_Queue::TEST_STATUS_FAILED);
                    if (Mage::getStoreConfigFlag(self::CONFIG_QUEUE_THROW_EXCEPTION)) {
                        $queue->save();
                        throw new Magium_Clairvoyant_Model_FailedTestException('Magium test has failed');
                    }
                    Mage::getSingleton('adminhtml/session')->addError(
                        Mage::helper('magium_clairvoyant')->__(
                            'Magium test %s failed',
                            $test->getName()
                        )
                    );
                }
                $queue->save();
            }

        }
    }

}
