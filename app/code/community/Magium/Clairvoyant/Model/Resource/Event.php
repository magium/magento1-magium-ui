<?php

/**
 * Class Magium_Clairvoyant_Model_Resource_Instruction
 *
 */

class Magium_Clairvoyant_Model_Resource_Event extends Mage_Core_Model_Resource_Db_Abstract
{

    protected function _construct()
    {
        $this->_init('magium_clairvoyant/event', 'entity_id');
    }

}
