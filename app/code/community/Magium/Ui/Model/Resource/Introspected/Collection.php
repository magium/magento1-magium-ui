<?php

class Magium_Ui_Model_Resource_Introspected_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{

    protected function _construct()
    {
        $this->_init('magium_ui/introspected');
    }
}
