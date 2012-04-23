<?php

namespace Amazon\Interfaces;

interface Authenticated
{
    public function authenticate(Credentials $credentials);
}
