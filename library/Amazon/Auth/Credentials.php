<?php

namespace Amazon\Auth;
use \Amazon\Interfaces\Credentials as ICredentials;

class Credentials implements ICredentials
{
    protected $auth_key = null;
    protected $secret = null;

    public function getAuthKey()
    {
        if (is_null($this->auth_key))
        {
            throw new \UnexpectedValueException('No Auth Key set');
        }

        return $this->auth_key;
    }

    protected function getSecret()
    {
        if (is_null($this->secret))
        {
            throw new \UnexpectedValueException('No Secret set');
        }

        return $this->secret;
    }

    public function setAuthKey($key)
    {
        $this->auth_key = $key;

        return $this;
    }

    public function setSecret($secret)
    {
        $this->secret = $secret;

        return $this;
    }

    public function getAuthHeader($nonce)
    {
        $auth = sprintf('AWS3-HTTPS AWSAccessKeyId=%s,Algorithm=HmacSHA256,Signature=%s', $this->getAuthKey(), $this->getSignature($nonce));

        return $auth;
    }

    public function getSignature($nonce)
    {
        return base64_encode(hash_hmac('sha256', $nonce, $this->getSecret(), true));
    }

}
