<?php

class Magium_Clairvoyant_Model_Cron
{

    const CONFIG_RETRIEVE_NEWS = 'magium/general/get_news';
    const CONFIG_FEED_LOCATION = 'magium/feed_location';
    const CONFIG_NOTIFICATION_EMAILS = 'magium/general/email_report';
    const CONFIG_NOTIFICATION_LEVEL = 'magium/general/report_when';

    public function retrieveNewsFeed()
    {
        if (Mage::getStoreConfigFlag(self::CONFIG_RETRIEVE_NEWS)) {
            $feedLocation = (string)Mage::app()->getConfig()->getNode(self::CONFIG_FEED_LOCATION);
            try {
                $reader = new Zend_Feed_Atom($feedLocation);
            } catch (Exception $e) {
                Mage::log($e->getMessage());
                return;
            }
            $inbox = Mage::getModel('adminnotification/inbox');

            if ($inbox instanceof Mage_AdminNotification_Model_Inbox) {
                foreach ($reader as $entry) {
                    if ($entry instanceof Zend_Feed_Entry_Abstract) {
                        $title = (string)$entry->title;
                        $summary = (string)$entry->summary;
                        $url = (string)$entry->link['href'];
                        $inbox->load($url, 'url');
                        if (!$inbox->getId()) {
                            $inbox->addNotice($title, $summary, $url);
                        }
                    }
                }
            }
        }
    }

    public function executeQueuedTests()
    {
        $queuedCollection = Mage::getModel('magium_clairvoyant/queue')->getCollection();
        if ($queuedCollection instanceof Magium_Clairvoyant_Model_Resource_Queue_Collection) {
            $queuedCollection->addFieldToFilter('status', Magium_Clairvoyant_Model_Queue::TEST_STATUS_QUEUED);
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
        $testInstance = Mage::getModel('magium_clairvoyant/test');
        if ($test instanceof Magium_Clairvoyant_Model_Instruction_Test
            && $testInstance Instanceof Magium_Clairvoyant_Model_Test) {
            $testInstance->load($queue->getTestId());
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

            $queue->setStatus(Magium_Clairvoyant_Model_Queue::TEST_STATUS_IN_PROCESS);
            $queue->setExecutedAt(Varien_Date::now());
            $queue->save();

            $result = $test->run();

            $queue->setLog(serialize($writer->getEvents()));
            $queue->setCompletedAt(Varien_Date::now());

            $passed = $result->passed();
            $skipped = $result->skipped();

            if (count($passed) == 1) {
                $queue->setStatus(Magium_Clairvoyant_Model_Queue::TEST_STATUS_PASSED);
            } else if (count($skipped) == 1) {
                $queue->setStatus(Magium_Clairvoyant_Model_Queue::TEST_STATUS_SKIPPED);
            } else {
                $queue->setStatus(Magium_Clairvoyant_Model_Queue::TEST_STATUS_FAILED);
            }
            $queue->save();
            $this->_sendNotification($queue, $testInstance, $writer);

        }
    }

    protected function _sendNotification(
        Magium_Clairvoyant_Model_Queue $queue,
        Magium_Clairvoyant_Model_Test $testInstance,
        Magium_Clairvoyant_Model_Logger_Events $logger
    )
    {
        $notificationLevel = Mage::getStoreConfig(self::CONFIG_NOTIFICATION_LEVEL, $testInstance->getStoreId());
        $doReport = false;
        if ($notificationLevel == Magium_Clairvoyant_Model_Source_ReportWhen::WHEN_SUCCESS) {
            $doReport = true;
        } else if ( $notificationLevel == Magium_Clairvoyant_Model_Source_ReportWhen::WHEN_SKIPPED) {
            if ($queue->getStatus() == Magium_Clairvoyant_Model_Queue::TEST_STATUS_FAILED
                || $queue->getStatus() == Magium_Clairvoyant_Model_Queue::TEST_STATUS_SKIPPED) {
                $doReport = true;
            }
        } else if ( $notificationLevel == Magium_Clairvoyant_Model_Source_ReportWhen::WHEN_FAILURE) {
            if ($queue->getStatus() == Magium_Clairvoyant_Model_Queue::TEST_STATUS_FAILED) {
                $doReport = true;
            }
        }

        if ($doReport) {
            $emailAddresses = Mage::getStoreConfig(self::CONFIG_NOTIFICATION_EMAILS, $testInstance->getStoreId());
            $emailAddresses = explode(',', $emailAddresses);

            $subject = sprintf('Magium test run result for test "%s".  Status: %s', $queue->getName(), $queue->getStatus());
            $message = <<<TEXT
This is a report for the Magium test run {$queue->getName()} run on {$queue->getCompletedAt()} with a resulting status of {$queue->getStatus()}.

Event Logs:


TEXT;

            $events = $logger->getEvents();
            foreach ($events as $event) {
                $message .= sprintf("%s\n", $event['message']);
                foreach ($event['extra'] as $type => $extra) {
                    $message .= sprintf("\t%s: %s\n", $type, $extra);
                }
                $message .= "\n";
            }

            $from = Mage::getStoreConfig('trans_email/ident_general/email');

            foreach ($emailAddresses as $emailAddress) {
                $this->_sendEmail($emailAddress, $subject, $message, $from);
            }
        }
    }

    protected function _sendEmail($email, $subject, $body, $from)
    {
        $mail = Mage::getModel('core/email');
        if ($mail instanceof Mage_Core_Model_Email) {
            $mail->setToEmail($email)
                ->setBody($body)
                ->setSubject($subject)
                ->setFromEmail($from)
                ->setType('text');
            try {
                $mail->send();
            } catch (Exception $e) {
                Mage::log($e->getMessage(), Zend_Log::ERR);
            }
        }
    }

}
