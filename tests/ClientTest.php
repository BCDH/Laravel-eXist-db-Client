<?php namespace BCDH\ExistDbClient;

class ClientTest extends SetupTest
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