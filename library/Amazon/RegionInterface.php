<?php

namespace Amazon;

interface RegionInterface
{
    public function isSESEnabled();
    public function getSESHost();
    public function getS3Host();
}
