<?php

namespace Amazon\Service;

use \Amazon\Interfaces;


class SES implements Interfaces\Authenticated
{
    protected $credentials;
    protected $region;
    
    public function authenticate(Interfaces\Credentials $credentials)
    {
        $this->credentials = $credentials;
    }
    
    public function __construct(\Amazon\Interfaces\Region $region, \Amazon\Interfaces\Credentials $credentials)
    {
        $this->authenticate($credentials);
        $this->region = $region;
    }
    
    public function listVerifiedEmailAddresses()
    {
    }
    
    public function verifyEmailAddress($email)
    {
    }
    
    public function deleteVerifiedEmailAddress($email)
    {
    }
    
    public function getSendQuota()
    {
    }
    
    public function getSendStatistics()
    {
        
    }
    
    public function sendEmail($message)
    {
        
    }
}

