<?php

class Magium_Clairvoyant_Model_Source_ReportWhen
{
    const WHEN_SUCCESS = 'success';
    const WHEN_FAILURE = 'failure';
    const WHEN_SKIPPED = 'skipped';

    public function toOptionArray()
    {
        return [
            self::WHEN_SKIPPED => 'Skipped',
            self::WHEN_FAILURE => 'Failure',
            self::WHEN_SUCCESS => 'Success',
        ];
    }

}
