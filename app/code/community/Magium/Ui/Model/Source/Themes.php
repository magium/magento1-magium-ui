<?php

class Magium_Ui_Model_Source_Themes
{

    public function toOptionArray()
    {
        $reflection = new ReflectionClass(\Magium\Magento\Themes\Magento19\ThemeConfiguration::class);
        $filename = $reflection->getFileName();
        $baseMagiumDir = dirname($filename, 2);
    }

}