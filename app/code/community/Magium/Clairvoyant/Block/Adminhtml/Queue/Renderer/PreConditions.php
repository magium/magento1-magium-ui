<?php

class Magium_Clairvoyant_Block_Adminhtml_Queue_Renderer_PreConditions extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    public function render(Varien_Object $row)
    {
        $conditions = unserialize($this->_getValue($row));
        return implode('<br>', $conditions);
    }

}
