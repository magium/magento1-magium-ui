<?php

class Magium_Clairvoyant_Model_Logger_Events extends \Zend\Log\Writer\AbstractWriter
{
    protected $_events = [];

    protected function doWrite(array $event)
    {
        $this->_events[] = $event;
    }

    public function getEvents()
    {
        return $this->_events;
    }

}
