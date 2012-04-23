<?php

namespace Amazon\Interfaces;

interface Request
{
    public function setUserAgent($user_agent);
    public function setCredentials(\Amazon\Interfaces\Credentials $credentials);
    public function setAction($action);
    public function setHost($host);
    public function setPath($path);
    public function getResponse();
    public function addParameter($key, $value);
    public function setParameters($params);
    public function getUrl();
    public function setMethod($method);

}
