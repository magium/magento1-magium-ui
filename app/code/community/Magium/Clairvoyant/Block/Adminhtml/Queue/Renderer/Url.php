<?php

class Magium_Clairvoyant_Block_Adminhtml_Queue_Renderer_Url extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    public function render(Varien_Object $row)
    {
        $output = sprintf('<a href="%s" style="
            width: 200px;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            " title="%s" target="_blank">%s</a>', $this->_getValue($row), $this->_getValue($row), $this->_getValue($row));

        return $output;
    }

}
