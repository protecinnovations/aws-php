<?php

namespace Amazon\Region;

abstract class AbstractRegion
{
    public function isSESEnabled()
    {
        return false;
    }

    public function getSESHost()
    {
        throw new \RuntimeException('SES Is not enabled in this region');
    }

    public function getS3Host()
    {
        return 's3.amazonaws.com';
    }
}
