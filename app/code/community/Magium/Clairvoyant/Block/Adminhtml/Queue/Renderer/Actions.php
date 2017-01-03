<?php

class Magium_Clairvoyant_Block_Adminhtml_Queue_Renderer_Actions extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    public function render(Varien_Object $row)
    {
        $value = $this->_getValue($row);
        $actions = unserialize($value);
        $outputs = [];
        foreach ($actions as $action) {
            if ($action instanceof \Magium\TestCase\Configurable\InstructionInterface) {
                $output = sprintf('%s::%s', $action->getClassName(), $action->getMethod());
                if ($action->getParams()) {
                    $output .= sprintf('<br> <span style="margin-left: 25px; ">Param: %s</span>', $action->getParams());
                }
                $outputs[] = $output;
            }
        }

        return implode('<br>', $outputs);
    }

}
