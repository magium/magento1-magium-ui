<?php

class Magium_Clairvoyant_Model_Resource_Option extends Mage_Core_Model_Resource_Db_Abstract
{

    public function _construct()
    {
        $this->_init('magium_clairvoyant/option', 'entity_id');
    }

}
