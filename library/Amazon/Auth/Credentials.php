<?php

namespace Amazon\Auth;
use \Amazon\CredentialsInterface as ICredentials;

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

    public function getRestAuthHeader($method, $path, &$headers)
    {
        if (!is_array($headers)) {
            $headers = array($headers);
        }

        $type = $md5 = $date = '';

        // Search for the Content-type, Content-MD5 and Date headers
        foreach ($headers as $key => $val) {
            if (strcasecmp($key, 'content-type') == 0) {
                $type = $val;
            }
            else if (strcasecmp($key, 'content-md5') == 0) {
                $md5 = $val;
            }
            else if (strcasecmp($key, 'date') == 0) {
                $date = $val;
            }
        }

        // If we have an x-amz-date header, use that instead of the normal Date
        if (isset($headers['x-amz-date']) && isset($date)) {
            $date = '';
        }

        $sig_str = "$method\n$md5\n$type\n$date\n";

        // For x-amz- headers, combine like keys, lowercase them, sort them
        // alphabetically and remove excess spaces around values
        $amz_headers = array();

        foreach ($headers as $key => $val) {

            $key = strtolower($key);
            if (substr($key, 0, 6) == 'x-amz-') {

                if (is_array($val)) {
                    $amz_headers[$key] = $val;
                }
                else {
                    $amz_headers[$key][] = preg_replace('/\s+/', ' ', $val);
                }
            }
        }
        if (!empty($amz_headers)) {

            ksort($amz_headers);
            foreach ($amz_headers as $key => $val) {
                $sig_str .= $key . ':' . implode(',', $val) . "\n";
            }
        }

        $sig_str .= '/'.parse_url($path, PHP_URL_PATH);

        if (strpos($path, '?location') !== false) {
            $sig_str .= '?location';
        }
        else if (strpos($path, '?acl') !== false) {
            $sig_str .= '?acl';
        }
        else if (strpos($path, '?torrent') !== false) {
            $sig_str .= '?torrent';
        }
        else if (strpos($path, '?versions') !== false) {
            $sig_str .= '?versions';
        }

        $signature = base64_encode(
            hash_hmac('sha1', utf8_encode($sig_str), $this->getSecret(), 1)
        );

        $headers['Authorization'] = 'AWS ' . $this->getAuthKey() . ':' . $signature;

        return $headers;
    }

}
