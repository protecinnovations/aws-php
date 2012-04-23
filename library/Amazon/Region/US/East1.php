<?php

namespace Amazon\Region\US;
use \Amazon\Interfaces;

class East1 extends \Amazon\Region\AbstractRegion implements Interfaces\Region
{
    public function isSESEnabled()
    {
        return true;
    }

    public function getSESHost()
    {
        return 'email.us-east-1.amazonaws.com';
    }
}
