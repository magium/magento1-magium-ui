<?php

class Magium_Clairvoyant_Model_Instruction_Test extends \Magium\Magento\AbstractMagentoTestCase
{
    protected $_baseUrl;

    /**
     * @var \Magium\TestCase\Configurable\InstructionsCollection
     */

    protected $_instructions;

    public function setInstructions(\Magium\TestCase\Configurable\InstructionsCollection $instructions)
    {
        $this->_instructions = $instructions;
    }

    public function setBaseUrl($url)
    {
        $this->_baseUrl = $url;
    }

    public function testExecute()
    {
        if ($this->_baseUrl) {
            $this->getTheme()->set('baseUrl', $this->_baseUrl);
        }
        $this->commandOpen($this->getTheme()->getBaseUrl());
        if (!$this->_instructions instanceof \Magium\TestCase\Configurable\InstructionsCollection) {
            throw new \Magium\TestCase\Configurable\InvalidInstructionException('Missing the instruction collection');
        }
        $this->_instructions->execute();
    }

}
