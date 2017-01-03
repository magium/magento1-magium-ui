<?php

class Magium_Clairvoyant_Block_Adminhtml_Queue_Renderer_Status extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    const COLORS = [
        Magium_Clairvoyant_Model_Observer::TEST_STATUS_PASSED       => '#2d9b17',
        Magium_Clairvoyant_Model_Observer::TEST_STATUS_QUEUED       => '#17459b',
        Magium_Clairvoyant_Model_Observer::TEST_STATUS_FAILED       => '#c40b0b',
        Magium_Clairvoyant_Model_Observer::TEST_STATUS_IN_PROCESS   => '#9400ff',
        Magium_Clairvoyant_Model_Observer::TEST_STATUS_SKIPPED      => '#ff9400',
    ];

    public function render(Varien_Object $row)
    {
        return sprintf('<div style="color: %s">%s</div>', self::COLORS[$this->_getValue($row)], $this->_getValue($row));
    }

}
