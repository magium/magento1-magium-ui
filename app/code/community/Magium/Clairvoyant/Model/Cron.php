<?php

class Magium_Clairvoyant_Model_Cron
{

    public function executeQueuedTests()
    {
        $queuedCollection = Mage::getModel('magium_clairvoyant/queue')->getCollection();
        if ($queuedCollection instanceof Magium_Clairvoyant_Model_Resource_Queue_Collection) {
            $queuedCollection->addFieldToFilter('status', Magium_Clairvoyant_Model_Observer::TEST_STATUS_QUEUED);
            foreach ($queuedCollection as $queued) {
                $this->_executeQueuedTest($queued);
            }
        }
    }

    protected function _executeQueuedTest(Magium_Clairvoyant_Model_Queue $queue)
    {
        $url = $queue->getCommandOpen();
        $preConditions = unserialize($queue->getPreConditions());
        $actions = unserialize($queue->getActionsSerialized());

        $test = Mage::helper('magium_clairvoyant')->getInstructionTestCase();
        if ($test instanceof Magium_Clairvoyant_Model_Instruction_Test) {
            $test->setBaseUrl($url);
            $test->setPreconditions($preConditions);
            $instructions = $test->get(\Magium\TestCase\Configurable\InstructionsCollection::class);
            if (!$instructions instanceof \Magium\TestCase\Configurable\InstructionsCollection) {
                return;
            }
            foreach ($actions as $action) {
                $instructions->addInstruction($action);
            }
            $test->setInstructions($instructions);


            $logger = $test->getLogger();
            $writer = Mage::getModel('magium_clairvoyant/logger_events');
            if ($writer instanceof Magium_Clairvoyant_Model_Logger_Events) {
                $logger->addWriter($writer);
            }

            $queue->setStatus(Magium_Clairvoyant_Model_Observer::TEST_STATUS_IN_PROCESS);
            $queue->setExecutedAt(Varien_Date::now());
            $queue->save();

            $result = $test->run();

            $queue->setLog(serialize($writer->getEvents()));
            $queue->setCompletedAt(Varien_Date::now());

            $passed = $result->passed();
            $skipped = $result->skipped();

            if (count($passed) == 1) {
                $queue->setStatus(Magium_Clairvoyant_Model_Observer::TEST_STATUS_PASSED);
            } else if (count($skipped) == 1) {
                $queue->setStatus(Magium_Clairvoyant_Model_Observer::TEST_STATUS_SKIPPED);
            } else {
                $queue->setStatus(Magium_Clairvoyant_Model_Observer::TEST_STATUS_PASSED);
            }
            $queue->save();

        }
    }

}
