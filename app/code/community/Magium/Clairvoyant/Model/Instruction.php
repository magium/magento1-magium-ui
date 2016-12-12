<?php

/**
 * Class Magium_Clairvoyant_Model_Instruction
 *
 * @method setType($type)
 * @method setClass($class)
 * @method setParam($param)
 * @method setTestId($testId)
 * @method getType()
 * @method getClass()
 * @method getParam()
 * @method getTestId()
 */

class Magium_Clairvoyant_Model_Instruction extends Mage_Core_Model_Abstract
{

    protected function _construct()
    {
        $this->_init('magium_clairvoyant/instruction');
    }

}
