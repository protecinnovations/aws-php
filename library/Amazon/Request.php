<?php

namespace Amazon;

class Request
{
    protected $action = null;
    protected $params = array();
    protected $headers = array();
    protected $host = null;
    protected $path = '';
    protected $credentials = null;
    protected $user_agent = 'AWS-PHP/1.0';

    const METHOD_UNKNOWN = 0;
    const METHOD_GET = 1;
    const METHOD_POST = 2;
    const METHOD_PUT = 3;
    const METHOD_DELETE = 4;
    const METHOD_HEAD = 5;
    const METHOD_OPTIONS = 6;
    const METHOD_TRACE = 7;
    const METHOD_CONNECT = 8;

    protected $method = self::METHOD_UNKNOWN;

    public function setUserAgent($user_agent)
    {
        $this->user_agent = $user_agent;

        return $this;
    }

    public function setCredentials(\Amazon\CredentialsInterface $credentials)
    {
        $this->credentials = $credentials;

        return $this;
    }

    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    protected function getAction()
    {
        return $this->action;
    }

    protected function addHeader($key, $value)
    {
        $this->headers[] = sprintf('%s: %s', $key, $value);
    }

    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    protected function checkValidity()
    {
        if (is_null($this->action) || empty($this->action))
        {
            throw new \RuntimeException('No action set');
        }

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

        $params[] = 'Action=' . $this->action;

        foreach ($this->params AS $key => $value)
        {
            if (is_array($value))
            {
                foreach ($value AS $v)
                {
                    $params[] = sprintf('%s=%s', $key, $v);
                }
            }
            else
            {
                $params[] = sprintf('%s=%s', $key, $value);
            }
        }

        $date = gmdate('D, d M Y H:i:s e');
        $this->addHeader('Date', $date);
        $this->addHeader('Host', $this->host);
        $this->addHeader('X-Amzn-Authorization', $this->credentials->getAuthHeader($date));

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_USERAGENT, $this->user_agent);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);

        switch ($this->method)
        {
            case self::METHOD_UNKNOWN:
                throw new \RuntimeException('No Method set');
                break;
            case self::METHOD_DELETE:
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
            case self::METHOD_GET:
                $this->setPath(sprintf('%s?%s', $this->path, implode('&', $params)));
                break;
            case self::METHOD_POST:
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, implode('&', $params));
                $this->addHeader('Content-Type', 'application/x-www-form-urlencoded');
                break;
            default:
                throw new \RuntimeException('Method not implemented');
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

        $return_xml = simplexml_load_string($return);

        $info = curl_getinfo($curl);

        return array(
            'info' => $info,
            'response' => $return_xml,
        );
    }

    public function addParameter($key, $value)
    {
        if (!isset($this->params["$key"]))
        {
            $this->params["$key"] = $value;
        }
        else
        {
            if (is_array($this->params["$key"]))
            {
                $this->params["$key"][] = $value;
            }
            else
            {
                $old_value = $this->params["$key"];
                $this->params["$key"] = array(
                    $old_value,
                    $value
                );
            }
        }
    }

    public function setParameters($params)
    {
        $this->params = $params;
    }

    public function getUrl()
    {
        return sprintf('https://%s/%s', $this->host, $this->path);
    }

    public function setMethod($method)
    {
        $this->method = $method;
    }
}
