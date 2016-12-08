<?php

class Magium_Ui_Model_Source_BaseTypes
{

    public function toOptionArray()
    {
        $node = Mage::getConfig()->getNode('magium/base_types');
        $return = [];
        foreach ($node->children() as $key => $child) {
            $return[] = [
                'value' => $key,
                'label' => (string)$child->label
            ];
        }
        return $return;
    }

}
