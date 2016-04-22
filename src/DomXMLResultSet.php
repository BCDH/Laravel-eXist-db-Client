<?php

namespace BCDH\ExistDbClient;

use DOMElement;

class DomXMLResultSet extends ResultSet
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

        return new DomXMLResult($result->scalar);
    }

    public function current()
    {
        return new DomXMLResult($this->retrieve()->scalar);
    }

    public function transform($rootTagName, $results, $view)
    {
        $root = new DomXMLResult("<$rootTagName></$rootTagName>");
        $rootDocument = $root->getDocument();

        foreach($results as $res) {
            /** @var DOMElement $res */
            $resultDocument = $res->getDocument();

            $this->appendChild($rootDocument, $resultDocument);
        }

        return $root->transform($view);
    }

    private function appendChild($rootDom, $newDom) {
        $newNode  = $rootDom->ownerDocument->importNode($newDom, TRUE);

        $rootDom->appendChild($newNode);
    }
}