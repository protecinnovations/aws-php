<?php

namespace Amazon\Service;

use \Amazon\CredentialsInterface;
use \Amazon\S3\RequestInterface;
use \Amazon\RegionInterface;
use \Amazon\Region\Factory as RegionFactory;
use \Amazon\S3\Request;

class S3 implements S3Interface
{
    protected $credentials;
    protected $region;
    protected $request = null;

    public function setCredentials(CredentialsInterface $credentials)
    {
        $this->credentials = $credentials;
    }

    protected function getCredentials()
    {
        if (is_null($this->credentials))
        {
            throw new RuntimeException('No credentials set');
        }

        return $this->credentials;
    }

    public function authenticate(CredentialsInterface $credentials)
    {
        $this->credentials = $credentials;
    }

    public function __construct(CredentialsInterface $credentials = null, RequestInterface $request = null, RegionInterface $region = null)
    {
        if (!is_null($credentials)) {
            $this->setCredentials($credentials);
        }

        if (is_null($request)) {
            $request = new Request();
        }

        if (is_null($region)) {
            $factory = new RegionFactory();
            $region = $factory->getRegion('us-east-1');
        }

        $this->region = $region;
        $this->request = $request;
    }

    public function getObject($path)
    {
        $bucket = $this->getBucketFromPath($path);
        $object = $this->getObjectFromPath($path);

        $this->request->setBucket($bucket);
        $this->request->setHost($this->region->getS3Host());
        $this->request->setCredentials($this->getCredentials());
        $this->request->setMethod(Request::METHOD_GET);
        $this->request->setPath($object);

        $response = $this->request->getResponse();

        if ($response['info']['http_code'] != 200) {
            return false;
        }

        return $response['response_raw'];
    }

    protected function getBucketFromPath($path)
    {
        $parts = explode('/', $path);

        $bucket = $parts[0];

        $len = strlen($bucket);

        if ($len < 3 || $len > 255) {
            throw new \LengthException("Bucket name \"$bucket\" must be between 3 and 255 characters long");
        }

        if (preg_match('/[^a-z0-9\._-]/', $bucket)) {
            throw new \InvalidArgumentException("Bucket name \"$bucket\" contains invalid characters");
        }

        if (preg_match('/(\d){1,3}\.(\d){1,3}\.(\d){1,3}\.(\d){1,3}/', $bucket)) {
            throw new \InvalidArgumentException("Bucket name \"$bucket\" cannot be an IP address");
        }
        return $bucket;
    }

    protected function getObjectFromPath($path)
    {
        $parts = explode('/', $path);

        array_shift($parts);

        if (count($parts) == 0) {
            return $path;
        }

        return join('/', array_map('rawurlencode', $parts));
    }
}