<?php

/**
 * Class Magium_Clairvoyant_Model_Resource_Instruction
 */

class Magium_Clairvoyant_Model_Resource_Instruction extends Mage_Core_Model_Resource_Db_Abstract
{

    protected function _construct()
    {
        $this->_init('magium_clairvoyant/instruction', 'entity_id');
    }

    public function deleteTestInstructions(Magium_Clairvoyant_Model_Test $test)
    {
        if ($test->getId()) {
            $adapter = $this->_getWriteAdapter();
            $adapter->delete(
                $this->getMainTable(),
                $adapter->quoteInto('test_id = ?', $test->getId())
            );
        }
    }

}
