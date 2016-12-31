<?php

class Magium_Clairvoyant_Model_Resource_Queue_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{

    protected function _construct()
    {
        $this->_init('magium_clairvoyant/queue');
    }

}
