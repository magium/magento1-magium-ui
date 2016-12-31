<?php

/**
 * Class Magium_Clairvoyant_Model_Queue
 *
 * @method setCommandOpen($url)
 * @method setName($name)
 * @method setEvent($event)
 * @method setPreConditions($conditions)
 * @method setActionsSerialized(string $serialized)
 * @method setLog(string $serialized)
 * @method setStatus(string $status)
 * @method setCreatedAt($date)
 * @method setExecutedAt($date)
 * @method string getCommandOpen()
 * @method string getName()
 * @method string getEvent()
 * @method string getPreConditions()
 * @method getActionsSerialized()
 * @method getLog()
 * @method getStatus()
 * @method getCreatedAt()
 * @method getExecutedAt()
 */

class Magium_Clairvoyant_Model_Queue extends Mage_Core_Model_Abstract
{

    protected function _construct()
    {
        $this->_init('magium_clairvoyant/queue');
    }


}
