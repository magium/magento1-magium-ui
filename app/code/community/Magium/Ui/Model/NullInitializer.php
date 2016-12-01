<?php

class Magium_Ui_Model_NullInitializer extends \Magium\TestCase\Initializer
{

    protected function configureWebDriver(\Magium\AbstractTestCase $testCase)
    {
        // Disable
    }

    protected function initLoggingExecutor(\Magium\AbstractTestCase $testCase)
    {
        // Disable
    }

    protected function configureClairvoyant(\Magium\AbstractTestCase $testCase)
    {
        // Disable
    }

    protected function setCharacteristics(\Magium\AbstractTestCase $testCase)
    {
        // Disable
    }

    protected function getDefaultConfiguration()
    {
        return [
            'instance'  => [
                'preference' => [
                    'Zend\I18n\Translator\Translator' => ['Magium\Util\Translator\Translator']
                ],
                'Magium\Util\Log\Logger'   => [
                    'parameters'    => [
                        'options'   => [
                            'writers' => [
                                [
                                    'name' => 'Zend\Log\Writer\Noop',
                                    'options' => []
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }


}
