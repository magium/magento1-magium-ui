<?php

class Magium_Ui_Model_Source_Integrations
{

    public function toOptionArray()
    {
        $reflection = new ReflectionClass(\Magium\Magento\Themes\Magento19\ThemeConfiguration::class);
        $filename = $reflection->getFileName();
        $baseMagiumDir = realpath(dirname($filename) . '/..');

        $directoryIterator = new DirectoryIterator($baseMagiumDir);

        $options = [];


        foreach ($directoryIterator as $dir) {

            $testFile = $dir->getFileInfo()->getRealPath() . '/ThemeConfiguration.php';
            if (file_exists($testFile)) {
                $class = 'Magium\Magento\Themes\\' . $dir->getBasename() . '\ThemeConfiguration';
                $class = new ReflectionClass($class);
                if ($class->isSubclassOf(\Magium\Magento\Themes\AbstractThemeConfiguration::class)) {
                    $class = str_replace('Magium\Magento\Themes\\', '', $class->getName());
                    $options[$dir->getBasename() . '\ThemeConfiguration'] = $class;
                }
            }
        }
        return $options;
    }
}