<?php

namespace Amazon\S3;

use \Amazon\Request as BaseRequest;
use \Amazon\RequestInterface as BaseRequestInterface;

class Request extends BaseRequest implements RequestInterface, BaseRequestInterface
{
    protected $bucket;

    public function setBucket($bucket)
    {
        $this->bucket = $bucket;
        return $this;
    }

    protected function getFullHost()
    {
        $host = '';
        if (!is_null($this->bucket)) {
            $host .= $this->bucket . '.';
        }

        $host .= $this->host;
        return $host;
    }

    protected function getFullPath()
    {
        $path = '';
        if (!is_null($this->bucket)) {
            $path .= $this->bucket . '/';
        }

        $path .= $this->path;
        return $path;
    }

    protected function checkValidity()
    {
        if (is_null($this->host) || empty($this->host))
        {
            throw new \RuntimeException('No host set');
        }

        if (is_null($this->credentials))
        {
            throw new \RuntimeException('No Credentials set');
        }
    }

    public function getResponse()
    {
        $this->checkValidity();

        $params = array();

        $headers = array(
            'Accept' => '*/*',
            'User-Agent' => $this->user_agent,
            'Date' => gmdate(DATE_RFC1123, time()),
            'Host' => $this->getFullHost()
        );

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);

        switch ($this->method)
        {
            case self::METHOD_UNKNOWN:
                throw new \RuntimeException('No Method set');
                break;
            case self::METHOD_DELETE:
                $method = 'DELETE';
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
            case self::METHOD_GET:
                $method = 'GET';
                break;
            case self::METHOD_POST:
                $method = 'POST';
                curl_setopt($curl, CURLOPT_POST, true);
                $headers['Content-Type'] = 'application/x-www-form-urlencoded';
                break;
            default:
                throw new \RuntimeException('Method not implemented');
        }


        $headers = $this->credentials->getRestAuthHeader($method, $this->getFullPath(), $headers);

        foreach ($headers as $header => $value) {
            if ($header == 'Accept') {
                continue;
            }
            $this->addHeader($header, $value);
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $this->getUrl());
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

        curl_setopt($curl, CURLINFO_HEADER_OUT, true);

        $return = curl_exec($curl);

        if ($return === false)
        {
            throw new \RuntimeException('Could not connect to given URL');
        }

        $info = curl_getinfo($curl);

        return array(
            'info' => $info,
            'response_raw' => $return
        );
    }
}
