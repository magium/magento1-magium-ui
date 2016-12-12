<?php

class Magium_Clairvoyant_Model_Source_Themes
{

    public function toOptionArray()
    {
        $options = [];

        $collection = Mage::getModel('magium_clairvoyant/introspected')->getCollection();
        /* @var $collection Magium_Clairvoyant_Model_Resource_Introspected_Collection */
        $collection->addFieldToFilter('base_type', \Magium\Themes\ThemeInterface::class);
        foreach ($collection as $theme) {
            /* @var $theme Magium_Clairvoyant_Model_Introspected */
            $options[$theme->getClass()] = $theme->getClass();
        }

        return $options;
    }

}
