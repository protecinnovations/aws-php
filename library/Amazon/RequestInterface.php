<?php

namespace Amazon;

interface RequestInterface
{
    public function setUserAgent($user_agent);
    public function setCredentials(\Amazon\CredentialsInterface $credentials);
    public function setAction($action);
    public function setHost($host);
    public function setPath($path);
    public function getResponse();
    public function addParameter($key, $value);
    public function setParameters($params);
    public function getUrl();
    public function setMethod($method);

}
