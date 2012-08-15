<?php

namespace Amazon\Service;

class CloudSearch
{
    protected $search_endpoint = null;
    protected $document_endpoint = null;

    protected $search_path = '/2011-02-01/search';
    protected $document_batch_path = '/2011-02-01/documents/batch';

    public function getDocumentBatchPath()
    {
        return $this->document_batch_path;
    }

    public function setDocumentBatchPath($document_batch_path)
    {
        $this->document_batch_path = $document_batch_path;

        return $this;
    }


    protected $parameters = [];

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
        $uri = $this->getSearchEndpoint() . $this->getSearchPath() . '?' . $this->getParameters();

        $return = file_get_contents($uri);

        return json_decode($return);
    }


    public function booleanSearch($query_string)
    {
        $this->addParameter('bq', $query_string);

        $uri = $this->getSearchEndpoint() . $this->getSearchPath() . '?' . $this->getParameters();

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

        $uri = $this->getDocumentEndpoint() . $this->getDocumentBatchPath();

        $return = file_get_contents($uri, false, $ctx);

        return $return;
    }

    public function generateSdf($id, $action, $fields = array())
    {
        switch ($action) {
            case 'delete':
                return array(
                    'type' => "delete",
                    'id' => $id,
                    'version' => time()
                );
                break;
            case 'add':
                return array(
                    'type' => 'add',
                    'id' => $id,
                    'version' => time(),
                    'lang' => 'en', // only language supported atm ?
                    'fields' => $fields
                );
                break;
        }
    }
}