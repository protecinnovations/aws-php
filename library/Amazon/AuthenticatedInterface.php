<?php

namespace Amazon;

interface AuthenticatedInterface
{
    public function authenticate(Credentials $credentials);
}
