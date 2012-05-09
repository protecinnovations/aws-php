<?php

namespace Amazon;

interface CredentialsInterface
{
    public function getAuthHeader($nonce);
}
