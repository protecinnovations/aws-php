<?php

namespace Amazon\Service;

use \Amazon\Interfaces;


class SES implements Interfaces\Authenticated
{
    protected $credentials;
    protected $region;
    protected $request = null;
    
    public function authenticate(Interfaces\Credentials $credentials)
    {
        $this->credentials = $credentials;
    }
    
    public function __construct(\Amazon\Interfaces\Region $region, \Amazon\Interfaces\Credentials $credentials, \Amazon\Interfaces\SES\Request $request)
    {
        $this->authenticate($credentials);
        $this->region = $region;
        $this->request = $request;
        $this->request->setCredentials($credentials);
        $this->request->setHost($region->getSESHost());
    }
    
    public function listVerifiedEmailAddresses()
    {
        $this->request->setAction('ListVerifiedEmailAddresses');
        
        $response = $this->request->getResponse();

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
    
    public function sendEmail(\Amazon\SES\Message $message)
    {
        if (!$message->isValid())
        {
            throw new InvalidArgumentException('Message is not valid');
        }
        
    }
}

