<?php

class Magium_Clairvoyant_Model_Source_TypeOptions
{

    protected $_type;

    protected $_replacements = [
        '/Magium\\\\Magento\\\\[^\\\\]+\\\\/',
        '/Magium\\\\[^\\\\]+\\\\/',
    ];

    public function setType($type)
    {
        $this->_type = $type;
    }

    public function toOptionArray()
    {

        $introspection = Mage::getModel('magium_clairvoyant/introspected')->getCollection();
        /* @var $introspection Magium_Clairvoyant_Model_Resource_Introspected_Collection */
        if ($this->_type) {
            $type = $node = Mage::getConfig()->getNode('magium/base_types/' . $this->_type);
            $introspection->addFieldToFilter('base_type', (string)$type->type);
        }

        $results = [];

        foreach ($introspection as $item) {
            /* @var $item Magium_Clairvoyant_Model_Introspected */
            $name = $item->getClass();
            foreach ($this->_replacements as $replacement) {
                $name = preg_replace($replacement, '', $name);
            }
            $option = [
                'label' => $name,
                'value' => $item->getClass()
            ];
            $results[] = $option;
        }

        return $results;

    }

}
