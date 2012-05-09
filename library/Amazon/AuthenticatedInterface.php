<?php

namespace Amazon;

interface AuthenticatedInterface
{
    public function authenticate(CredentialsInterface $credentials);
}
