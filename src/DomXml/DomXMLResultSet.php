<?php

namespace BCDH\ExistDbClient\DomXml;

use BCDH\ExistDbClient\ResultInterface;
use BCDH\ExistDbClient\ResultSet;

class DomXMLResultSet extends ResultSet
{
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
            /** @var ResultInterface $res */
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