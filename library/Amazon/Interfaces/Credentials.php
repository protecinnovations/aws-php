<?php

namespace Amazon\Interfaces;

interface Credentials
{
    public function getAuthHeader($nonce);
}
