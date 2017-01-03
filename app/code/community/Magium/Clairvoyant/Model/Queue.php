<?php

/**
 * Class Magium_Clairvoyant_Model_Queue
 *
 * @method setCommandOpen($url)
 * @method setName($name)
 * @method setEvent($event)
 * @method setTestId($testId)
 * @method setPreConditions($conditions)
 * @method setActionsSerialized(string $serialized)
 * @method setLog(string $serialized)
 * @method setStatus(string $status)
 * @method setCreatedAt($date)
 * @method setCompletedAt($date)
 * @method setExecutedAt($date)
 * @method string getCommandOpen()
 * @method string getName()
 * @method string getEvent()
 * @method string getPreConditions()
 * @method getActionsSerialized()
 * @method getLog()
 * @method getTestId()
 * @method getStatus()
 * @method getCreatedAt()
 * @method getCompletedAt()
 * @method getExecutedAt()
 */

class Magium_Clairvoyant_Model_Queue extends Mage_Core_Model_Abstract
{

    const TEST_STATUS_PASSED = 'passed';
    const TEST_STATUS_QUEUED = 'queued';
    const TEST_STATUS_FAILED = 'failed';
    const TEST_STATUS_IN_PROCESS = 'in_process';
    const TEST_STATUS_SKIPPED = 'skipped';


    protected function _construct()
    {
        $this->_init('magium_clairvoyant/queue');
    }


}
