<?php

class Magium_Clairvoyant_Model_Resource_Configuration extends Mage_Core_Model_Resource_Db_Abstract
{

    protected function _construct()
    {
        $this->_init('magium_clairvoyant/configuration', 'entity_id');
    }

}
