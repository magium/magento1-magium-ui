<?php

class Magium_Clairvoyant_Model_Instruction_Test extends \Magium\Magento\AbstractMagentoTestCase
{
    protected $_baseUrl;

    /**
     * @var \Magium\TestCase\Configurable\InstructionsCollection
     */

    protected $_instructions;

    protected $_preConditions = [];

    public function setInstructions(\Magium\TestCase\Configurable\InstructionsCollection $instructions)
    {
        $this->_instructions = $instructions;
    }

    public function setBaseUrl($url)
    {
        $this->_baseUrl = $url;
    }

    public function setPreconditions(array $preConditions)
    {
        $this->_preConditions = $preConditions;
    }

    public function testExecute()
    {
        $executor = $this->get(\Magium\TestCase\Executor::class);
        if ($executor instanceof \Magium\TestCase\Executor) {
            foreach ($this->_preConditions as $condition) {
                $this->getLogger()->info('Evaluating precondition', ['condition' => $condition]);
                $result = $executor->evaluate($condition);
                if (!$result) {
                    $this->getLogger()->info('Condition evaluated to false', ['condition' => $condition]);
                    self::markTestSkipped('Evaluation returned false for ' . $condition);
                }
            }
        }
        Locale::setDefault(Mage::app()->getLocale()->getLocaleCode());
        if ($this->_baseUrl) {
            $this->getTheme()->set('baseUrl', $this->_baseUrl);
        }
        $this->getLogger()->info('Opening URL', ['url' => $this->getTheme()->getBaseUrl()]);
        $this->commandOpen($this->getTheme()->getBaseUrl());
        if (!$this->_instructions instanceof \Magium\TestCase\Configurable\InstructionsCollection) {
            throw new \Magium\TestCase\Configurable\InvalidInstructionException('Missing the instruction collection');
        }
        $this->_instructions->execute();
    }

}
