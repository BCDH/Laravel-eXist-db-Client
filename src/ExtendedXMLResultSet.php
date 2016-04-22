<?php

namespace BCDH\ExistDbClient;

use SimpleXMLElement;

class ExtendedXMLResultSet extends ResultSet
{
    function __construct($client, $resultId, $options)
    {
        parent::__construct($client, $resultId, $options);
    }

    public function getNextResult()
    {
        $result = $this->client->retrieve(
            $this->resultId,
            $this->currentHit,
            $this->options
        );

        $this->currentHit++;
        $this->hasMoreHits = $this->currentHit < $this->hits;

        return new ExtendedSimpleXMLElement($result->scalar);
    }

    public function current()
    {
        return new ExtendedSimpleXMLElement($this->retrieve()->scalar);
    }

    public function transform($rootTagName, $results, $view)
    {
        /** @var SimpleXMLElement $root */
        $root = simplexml_load_string("<$rootTagName></$rootTagName>");
        $rootDom = dom_import_simplexml($root);

        foreach($results as $res) {
            $this->appendChild($rootDom, $res);
        }

        $xml = $rootDom->ownerDocument->saveXML();
        $mergedXml = new ExtendedSimpleXMLElement($xml);

        return $mergedXml->transform($view);
    }

    private function appendChild($rootDom, $new) {
        $newDom  = dom_import_simplexml($new);
        $newNode  = $rootDom->ownerDocument->importNode($newDom, TRUE);

        $rootDom->appendChild($newNode);
    }
}