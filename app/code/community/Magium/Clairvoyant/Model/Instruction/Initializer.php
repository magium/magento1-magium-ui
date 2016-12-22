<?php

class Magium_Clairvoyant_Model_Instruction_Initializer extends \Magium\TestCase\Initializer
{
    protected function getDefaultConfiguration()
    {
        $configuration = $this->testCaseConfigurationObject->getWebDriverConfiguration();
        $magentoSetting = Mage::getStoreConfig('magium/general/selenium_url');
        if ($magentoSetting) {
            $configuration['url']['default'] = $magentoSetting;
        }

        $writers = [
            'name' => 'Zend\Log\Writer\Noop',
            'options' => []
        ];
        $magentoSetting = Mage::getStoreConfig('magium/general/log');
        if ($magentoSetting) {
            $writers = [
                'name' => \Zend\Log\Writer\Stream::class,
                'options' => [
                    'stream' => $magentoSetting
                ]
            ];
        }

        return [
            'definition' => [
                'class' => [
                    'Magium\WebDriver\WebDriver' => [
                        'instantiator' => 'Magium\WebDriver\WebDriverFactory::create'
                    ],

                    'Magium\WebDriver\WebDriverFactory' => [
                        'create'       => $configuration
                    ]
                ]
            ],
            'instance'  => [
                'preference' => [
                    'Zend\I18n\Translator\Translator' => ['Magium\Util\Translator\Translator']
                ],
                'Magium\Util\Log\Logger'   => [
                    'parameters'    => [
                        'options'   => [
                            'writers' => [
                                $writers
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
}
