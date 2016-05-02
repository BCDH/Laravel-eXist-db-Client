<?php

namespace BCDH\ExistDbClient\SimpleXml;

use BCDH\ExistDbClient\ResultSet;

class SimpleXMLResultSet extends ResultSet
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

        return new SimpleXMLResult($result->scalar);
    }

    public function current()
    {
        return new SimpleXMLResult($this->retrieve()->scalar);
    }

    public function transform($rootTagName, $results, $view)
    {
        /** @var SimpleXMLElement $root */
        $root = simplexml_load_string("<$rootTagName></$rootTagName>");
        $rootDom = dom_import_simplexml($root);

        foreach($results as $res) {
            $this->appendChild($rootDom, $res->getDocument());
        }

        $xml = $rootDom->ownerDocument->saveXML();
        $mergedXml = new SimpleXMLResult($xml);

        return $mergedXml->transform($view);
    }

    private function appendChild($rootDom, $new) {
        $newDom  = dom_import_simplexml($new);
        $newNode  = $rootDom->ownerDocument->importNode($newDom, TRUE);

        $rootDom->appendChild($newNode);
    }
}