<?php

namespace Amazon\Service;

class CloudSearch
{
    protected $search_endpoint = null;
    protected $document_endpoint = null;

    protected $api_version = '2011-03-01';

    protected $search_path = 'search';
    protected $document_batch_path = 'documents/batch';

    protected $parameters = [];

    const API_VERSION_OLD = '2011-03-01';
    const API_VERSION_NEW = '2013-01-01';

    public function getDocumentBatchPath()
    {
        return $this->document_batch_path;
    }

    public function setDocumentBatchPath($document_batch_path)
    {
        $this->document_batch_path = $document_batch_path;

        return $this;
    }

    public function setApiVersion($api_version)
    {
        $this->api_version = $api_version;

        return $this;
    }

    public function getApiVersion()
    {
        return $this->api_version;
    }

    public function getSearchPath()
    {
        return $this->search_path;
    }

    public function setSearchPath($search_path)
    {
        $this->search_path = $search_path;

        return $this;
    }

    public function getSearchEndpoint()
    {
        return $this->search_endpoint;
    }

    public function setSearchEndpoint($search_endpoint)
    {
        $this->search_endpoint = $search_endpoint;

        return $this;
    }

    public function getDocumentEndpoint()
    {
        return $this->document_endpoint;
    }

    public function setDocumentEndpoint($document_endpoint)
    {
        $this->document_endpoint = $document_endpoint;

        return $this;
    }

    public function search($query_string)
    {
        $this->addParameter('q', $query_string);

        $uri = sprintf(
            '%s/%s/%s?%s',
            $this->getSearchEndpoint(),
            $this->getApiVersion(),
            $this->getSearchPath(),
            $this->getParameters()
        );

        $return = file_get_contents($uri);

        return json_decode($return);
    }

    public function booleanSearch($query_string)
    {
        switch ($this->getApiVersion()) {
            case self::API_VERSION_OLD:
                $this->addParameter('bq', $query_string);
            break;

            case self::API_VERSION_NEW:
                $this->addParameter('q.parser', 'structured');
                $this->addParameter('q', $query_string);
            break;
        }

        $uri = sprintf(
            '%s/%s/%s?%s',
            $this->getSearchEndpoint(),
            $this->getApiVersion(),
            $this->getSearchPath(),
            $this->getParameters()
        );

        $return = file_get_contents($uri);

        return json_decode($return);
    }

    public function addParameter($key, $value)
    {
        $this->parameters[$key] = $value;

        return $this;
    }

    public function getParameters()
    {
        return http_build_query($this->parameters);
    }

    public function sendSdf($sdf_array)
    {
        $content = json_encode($sdf_array);

        $opts = [
            'http' => [
            'ignore_errors' => true,
                'method' => 'POST',
                'header' => implode(
                    "\r\n",
                    [
                        'Content-Type: application/json',
                        'Content-Length: ' . strlen($content),
                        'Accept: application/json'
                    ]
                ),
                'content' => $content
            ]
        ];

        $ctx = stream_context_create($opts);

        $uri = sprintf(
            '%s/%s/%s',
            $this->getDocumentEndpoint(),
            $this->getApiVersion(),
            $this->getDocumentBatchPath()
        );

        $this->getDocumentEndpoint() . $this->getDocumentBatchPath();

        $return = file_get_contents($uri, false, $ctx);

        return $return;
    }

    public function generateSdf($id, $action, $fields = array())
    {
        $id = strtolower($id);

        $id = preg_replace('/[^a-z0-9_]/', '_', $id);

        switch ($action) {
            case 'delete':
                if ($this->getApiVersion() == self::API_VERSION_OLD) {
                    return array(
                        'type' => "delete",
                        'id' => $id,
                        'version' => time()
                    );
                } else {
                    return array(
                        'type' => "delete",
                        'id' => $id
                    );
                }
                break;
            case 'add':
                if ($this->getApiVersion() == self::API_VERSION_OLD) {
                    return array(
                        'type' => 'add',
                        'id' => $id,
                        'version' => time(),
                        'lang' => 'en', // only language supported atm ?
                        'fields' => $fields
                    );
                } else {
                    return array(
                        'type' => 'add',
                        'id' => $id,
                        'fields' => $fields
                    );
                }
                break;
        }
    }
}
