php-eXist-db-Client for Laravel 5
=================================

A client that abstracts out the XML RPC calls for eXist-db.

##  Requirements:

- Laravel 5
- PHP 5.5 or PHP 7
- PEAR 1.10
```bash
sudo apt-get install php-pear
sudo pear channel-update pear.php.net
```

Usage
=====

```php
$connection = new \BCDH\ExistDbClient\ExistDbClient();

$stmt = $connection->prepareQuery('for $someNode in collection("/SomeCollection")/someNodeName[./somePredicateAttribute=$someValueToBeBound] return $someNode');
$stmt->setSimpleXMLReturnType();
$stmt->bindVariable('someValueToBeBound', '5');

$resultPool = $stmt->execute();
$result = $resultPool->getAllResults();

foreach($result as $xml) {    
    var_dump($xml->somePredicateAttribute);
}
```

## Installing

#### Add the service provider to your config/app.php:

    BCDH\ExistDbClient\ExistDbServiceProvider::class

#### Publish configuration:

**Laravel 5**

    php artisan vendor:publish
    
#### Specify database connection credentials on your `config/exist-db.php`

    [
        'protocol'  => "http",
        'user'      => "admin",
        'password'  => "admin",
        'host'      => "localhost",
        'port'      => 8080,
        'path'      => "/exist/xmlrpc/",
    ]