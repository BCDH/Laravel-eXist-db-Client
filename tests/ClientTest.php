<?php namespace BCDH\ExistDbClient;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ExistDbClient
     */
    private static $connection = null;

    /**
     * @var array
     */
    private static $config;

    /**
     * @var string
     */
    private static $collectionName = "CDCatalog";

    public static function setupBeforeClass()
    {
        self::$config = array(
            'uri' => 'http://admin:admin@localhost:8080/exist/xmlrpc/'
        );

        self::$connection = new ExistDbClient(self::$config);
    }

    public static function tearDownAfterClass()
    {
        self::$connection->removeCollection(self::$collectionName);
    }

    public function testCreateCollection()
    {
        self::$connection->createCollection(self::$collectionName);

        $this->assertTrue(true);
    }

    /**
     * @depends testCreateCollection
     */
    public function testInsertData()
    {
        $catalogAsSingleNode = simplexml_load_file(__DIR__ . '/xml/cd_catalog.xml');
        foreach ($catalogAsSingleNode->children() as $child) {
            $md5able = '';
            foreach ($child->children() as $property) {
                $md5able .= (string)$property;
            }

            self::$connection->storeDocument(
                self::$collectionName . '/' . md5($md5able) . '.xml',
                $child->asXML()
            );
        }

        $this->assertTrue(true);
    }

    /**
     * @depends testInsertData
     */
    public function testWhereQuery()
    {
        $xql = 'for $cd in /CD[./PRICE < $price] return $cd';

        /** @var Query $stmt */
        $stmt = self::$connection->prepareQuery($xql);
        $stmt->bindVariable('price', 8.70);

        $resultPool = $stmt->execute();
        $results = $resultPool->getAllResults();

        $count = count($results);
        $expected = 10;

        $this->assertEquals($expected, $count);
    }

    /**
     * @depends testInsertData
     */
    public function testWhereQueryWrongTypeConversion()
    {
        $xql = 'for $cd in /CD[./PRICE < $price] return $cd';

        /** @var Query $stmt */
        $stmt = self::$connection->prepareQuery($xql);
        $stmt->bindVariable('price', "8.70");

        $resultPool = $stmt->execute();
        $results = $resultPool->getAllResults();

        $count = count($results);
        $expected = 16;

        $this->assertEquals($expected, $count);
    }
    /**
     * @depends testInsertData
     */
    public function testWhereQueryEquals()
    {
        $stmt = self::$connection->prepareQuery('for $cd in collection("/'.self::$collectionName.'")/CD[./ARTIST=$artist] return $cd');
        $stmt->setSimpleXMLReturnType();
        $stmt->bindVariable('artist', 'Eros Ramazzotti');

        $resultPool = $stmt->execute();
        $result = $resultPool->getAllResults();

        $count = count($result);

        if($count != 1) {
            $this->assertTrue(false, "Wrong entries found: $count");
        }

        $xml = $result[0];
        $expectedPrice = "9.90";

        $this->assertEquals($expectedPrice, $xml->PRICE);
    }
}