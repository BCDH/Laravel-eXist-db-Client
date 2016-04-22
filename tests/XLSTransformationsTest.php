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

        $html = $res->transform(__DIR__.'/xml/cd_catalog.xsl');

        $expected1 = '<td>Empire Burlesque</td>';
        $expected2 = '<td>Bob Dylan</td>';

        $this->assertTrue(
            str_contains($html, $expected1) &&
            str_contains($html, $expected2)
        );
    }

    /**
     * @depends testInsertData
     */
    public function testSingleExtendedTransformation() {
        $stmt = self::$connection->prepareQuery('for $cd in collection("/'.self::$collectionName.'")/CD[./ARTIST=$artist] return $cd');
        $stmt->bindVariable('artist', 'Bonnie Tyler');
        $stmt->setExtendedXMLReturnType();

        $resultPool = $stmt->execute();
        $results = $resultPool->getAllResults();
        $res = $results[0];

        $html = $res->transform(__DIR__.'/xml/cd_catalog.xsl');

        $expected1 = '<td>Hide your heart</td>';
        $expected2 = '<td>Bonnie Tyler</td>';

        $this->assertTrue(
            str_contains($html, $expected1) &&
            str_contains($html, $expected2)
        );
    }

    /**
     * @depends testInsertData
     */
    public function testGroupExtendedTransformation() {
        $stmt = self::$connection->prepareQuery('for $cd in /CD return $cd');
        $stmt->setExtendedXMLReturnType();

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
            $xml .= '<tr>';
            $xml .= '<td>' . $r->TITLE . '</td>';
            $xml .= '<td>' . $r->ARTIST . '</td>';
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