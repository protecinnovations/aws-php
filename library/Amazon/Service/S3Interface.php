<?php

namespace Amazon\Service;

use \Amazon\CredentialsInterface;
use \Amazon\S3\RequestInterface;
use \Amazon\RegionInterface;

interface S3Interface
{
    public function __construct(
        CredentialsInterface $credentials = null,
        RequestInterface $request = null,
        RegionInterface $region = null
    );
    public function setCredentials(CredentialsInterface $credentials);
    public function authenticate(CredentialsInterface $credentials);
    public function getObject($path);
}