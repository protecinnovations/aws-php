<?php

namespace Amazon\Region\US;

class East1 extends \Amazon\Region\AbstractRegion implements \Amazon\RegionInterface
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
