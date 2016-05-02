<?php namespace BCDH\ExistDbClient;

use DOMElement;

class XLSTransformationsTest extends SetupTest
{
    public function testCreateCollection() {
        parent::createCollection();
    }

    /**
     * @depends testCreateCollection
     */
    public function testInsertData() {
        parent::insertData();
    }

    /**
     * @depends testInsertData
     */
    public function testSingleDomTransformation() {
        $stmt = self::$connection->prepareQuery('for $cd in collection("/'.self::$collectionName.'")/CD[./ARTIST=$artist] return $cd');
        $stmt->bindVariable('artist', 'Bob Dylan');
        $stmt->setDomXMLReturnType();

        $resultPool = $stmt->execute();
        $results = $resultPool->getAllResults();
        $res = $results[0];

        $html = $res->transform(__DIR__.'/xml/cd_catalog_simplified.xsl');
        $expected = $this->getExpectedXmlDom(array($res));

        $this->assertTrue(str_contains($html, $expected));
    }

    /**
     * @depends testInsertData
     */
    public function testSingleExtendedTransformation() {
        $stmt = self::$connection->prepareQuery('for $cd in collection("/'.self::$collectionName.'")/CD[./ARTIST=$artist] return $cd');
        $stmt->bindVariable('artist', 'Bonnie Tyler');
        $stmt->setSimpleXMLReturnType();

        $resultPool = $stmt->execute();
        $results = $resultPool->getAllResults();
        $res = $results[0];

        $html = $res->transform(__DIR__.'/xml/cd_catalog_simplified.xsl');
        $expected = $this->getExpectedXml(array($res));

        $this->assertTrue(str_contains($html, $expected));
    }

    /**
     * @depends testInsertData
     */
    public function testGroupExtendedTransformation() {
        $stmt = self::$connection->prepareQuery('for $cd in /CD return $cd');
        $stmt->setSimpleXMLReturnType();

        $resultPool = $stmt->execute();
        $results = $resultPool->getAllResults();
        $rootTagName = 'catalog';

        $html = $resultPool->transform($rootTagName, $results, __DIR__.'/xml/cd_catalog_simplified.xsl');
        $expected = $this->getExpectedXml($results);

        $this->assertTrue(str_contains($html, $expected));
    }

    /**
     * @depends testInsertData
     */
    public function testGroupDomTransformation() {
        $stmt = self::$connection->prepareQuery('for $cd in /CD return $cd');
        $stmt->setDomXMLReturnType();

        $resultPool = $stmt->execute();
        $results = $resultPool->getAllResults();
        $rootTagName = 'catalog';

        $html = $resultPool->transform($rootTagName, $results, __DIR__.'/xml/cd_catalog_simplified.xsl');
        $expected = $this->getExpectedXmlDom($results);

        $this->assertTrue(str_contains($html, $expected));
    }

    private function getExpectedXml($results) {
        $xml = '';
        foreach($results as $r) {
            $doc = $r->getDocument();
            $xml .= '<tr>';
            $xml .= '<td>' . $doc->TITLE . '</td>';
            $xml .= '<td>' . $doc->ARTIST . '</td>';
            $xml .= '</tr>';
        }
        return $xml;
    }

    private function getExpectedXmlDom($results) {
        $xml = '';
        foreach($results as $r) {
            /** @var DOMElement $doc */
            $doc = $r->getDocument();

            $xml .= '<tr>';
            $xml .= '<td>' . $doc->getElementsByTagName('TITLE')->item(0)->nodeValue . '</td>';
            $xml .= '<td>' . $doc->getElementsByTagName('ARTIST')->item(0)->nodeValue . '</td>';
            $xml .= '</tr>';
        }
        return $xml;
    }
}