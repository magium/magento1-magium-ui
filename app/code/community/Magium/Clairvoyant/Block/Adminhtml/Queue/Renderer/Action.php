<?php

class Magium_Clairvoyant_Block_Adminhtml_Queue_Renderer_Action extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    public function render(Varien_Object $row)
    {
        $entries = unserialize($this->_getValue($row));
        foreach ($entries as &$entry) {
            if ($entry['timestamp'] instanceof DateTime) {
                unset($entry['timestamp']);
            }
        }
        $entries = json_encode($entries);
        $output = sprintf('<a onclick="viewLog(this)" data-log="%s">View Log</a>', htmlentities($entries));
        return $output;
    }

}
