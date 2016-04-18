<?php

namespace BCDH\ExistDbClient;

class ExistDbClient
{
    /**
     *
     * The URI to the database instance inclusive of the port
     * @var $uri
     */
    protected $uri;

    /**
     * The xml rpc client for the instance
     * @var $connection
     */
    protected $connection;
    protected $options;
    protected $collection;

    /**
     * @var \XML_RPC2_Client
     */
    protected $client;

    protected $query;

    protected function defaultOptionValue()
    {

    }

    public function __construct($options = null)
    {
        if(function_exists('config')) {
            $defaults = config("exist-db");
        } else {
            $defaults = [
                'uri' => 'http://guest:guest@localhost:8080/exist/xmlrpc/'
            ];
        }

        if ($options) {
            if (isset($options['collection'])) {
                $this->collection = $options['collection'];
            }
            foreach ($defaults as $part => $value) {
                if (!isset($options[$part])) {
                    $options[$part] = $value;
                }
            }

            if (isset($options['uri'])) {
                $this->uri = $options['uri'];
            } else {
                $this->uri = $options['protocol'] . '://' . $options['user'] . ':' . $options['password'] . '@' . $options['host'] . ':' . $options['port'] . $options['path'];
            }
        } else {
            $this->uri = $defaults['protocol'] . '://' . $defaults['user'] . ':' . $defaults['password'] . '@' . $defaults['host'] . ':' . $defaults['port'] . $defaults['path'];
        }
        $this->conn = null;
        $this->client = \XML_RPC2_Client::create(
            $this->uri,
            array(
                'encoding' => 'utf-8'
            )
        );
    }

    /**
     * Inserts a new document into the database or replace an existing one:
     *
     * @param string $docName Path to the database location where the new document is to be stored.
     * @param string $xml XML content of this document as a UTF-8 encoded byte array.
     * @param boolean $overWrite Set this value to > 0 to automatically replace an existing document at the same location.
     * @link http://exist-db.org/exist/apps/doc/devguide_xmlrpc.xml?id=D2.2.4#D2.2.4.6
     */
    public function storeDocument($docName, $xml, $overWrite = false)
    {
        $this->client->parse($xml, $this->collection . $docName, $overWrite ? 1 : 0);
    }

    /**
     * Removes a document from the database.
     *
     * @param string $docName The full path to the database document.
     * @link http://exist-db.org/exist/apps/doc/devguide_xmlrpc.xml?id=D2.2.4#D2.2.4.7
     */
    public function deleteDocument($docName)
    {
        $this->client->remove($this->collection . $docName);
    }

    /**
     * Creates a new collection
     *
     * @param string $collectionName Path to the new collection.
     * @link http://exist-db.org/exist/apps/doc/devguide_xmlrpc.xml?id=D2.2.4#D2.2.4.7
     */
    public function createCollection($collectionName)
    {
        $this->client->createCollection($collectionName);
    }

    /**
     * Removes a collection from the database (including all of its documents and sub-collections).
     *
     * @param string $collectionName The full path to the collection.
     * @link http://exist-db.org/exist/apps/doc/devguide_xmlrpc.xml?id=D2.2.4#D2.2.4.8
     */
    public function removeCollection($collectionName)
    {
        $this->client->removeCollection($collectionName);
    }

    public function prepareQuery($xql)
    {
        $query = new Query($xql, $this->client, $this->collection);
        return $query;
    }
}