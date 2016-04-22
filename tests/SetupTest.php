<?php namespace BCDH\ExistDbClient;

/**
 * Please update include-path in composer.json to `vendor/pear/xml_rpc2/` and change admin credentials in this class in order to run TestUnit
 *
 * Class SetupTest
 * @package BCDH\ExistDbClient
 */
class SetupTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ExistDbClient
     */
    public static $connection = null;

    /**
     * @var array
     */
    public static $config;

    /**
     * @var string
     */
    public static $collectionName = "CDCatalog";

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

    public function tearDown() {
        echo "Time elapsed: " . $this->getTestResultObject()->time(). PHP_EOL;
    }

    public function testTrue() {
        $this->assertTrue(1 == 1);
    }

    protected function createCollection()
    {
        self::$connection->createCollection(self::$collectionName);

        $this->assertTrue(true);
    }

    /**
     * @depends testCreateCollection
     */
    protected function insertData()
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
}