<?php

class Magium_Clairvoyant_Model_Instruction_Initializer extends \Magium\TestCase\Initializer
{

    protected $_bypassWebDriver = false;

    public function bypassWebDriver($bypass = true)
    {
        $this->_bypassWebDriver = $bypass;
    }

    protected function configureWebDriver(\Magium\AbstractTestCase $testCase)
    {
        if (!$this->_bypassWebDriver) {
            parent::configureWebDriver($testCase);
        }
    }

    protected function initLoggingExecutor(\Magium\AbstractTestCase $testCase)
    {
        if (!$this->_bypassWebDriver) {
            parent::initLoggingExecutor($testCase);
        }
    }

    protected function configureClairvoyant(\Magium\AbstractTestCase $testCase)
    {
        if (!$this->_bypassWebDriver) {
            parent::configureClairvoyant($testCase);
        }
    }

    protected function setCharacteristics(\Magium\AbstractTestCase $testCase)
    {
        if (!$this->_bypassWebDriver) {
            parent::setCharacteristics($testCase);
        }
    }

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
