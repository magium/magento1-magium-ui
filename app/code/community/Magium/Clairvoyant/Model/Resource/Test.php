<?php

/**
 * Class Magium_Clairvoyant_Model_Resource_Test
 * @method getName()
 */

class Magium_Clairvoyant_Model_Resource_Test extends Mage_Core_Model_Resource_Db_Abstract
{

    protected function _construct()
    {
        $this->_init('magium_clairvoyant/test', 'entity_id');
    }

}
