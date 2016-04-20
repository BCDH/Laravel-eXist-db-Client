eXist-db Client for Laravel 5
=================================

A Laravel 5 package that abstracts out the XML RPC calls for [eXist-db](http://exist-db.org/exist/apps/homepage/index.html). Based on [php-eXist-db-Client](https://github.com/CuAnnan/php-eXist-db-Client).

##  Requirements:

- Laravel 5
- PHP 5.5 or PHP 7
- PEAR 1.10
```bash
sudo apt-get install php-pear
sudo pear channel-update pear.php.net
```

## Installing

- Add the service provider to your config/app.php:

    ```BCDH\ExistDbClient\ExistDbServiceProvider::class```

- Publish your configuration file:

     ```php artisan vendor:publish ```
    
- Edit your connection credentials in `config/exist-db.php`

    ```
    [
        'protocol'  => "http",
        'user'      => "admin",
        'password'  => "admin",
        'host'      => "localhost",
        'port'      => 8080,
        'path'      => "/exist/xmlrpc/",
    ]
    ```
    

## Usage


```php

use BCDH\ExistDbClient\ExistDbClient;

$connection = new ExistDbClient();

$stmt = $connection->prepareQuery('for $someNode in collection("/SomeCollection")/someNodeName[./somePredicateAttribute=$someValueToBeBound] return $someNode');
$stmt->setSimpleXMLReturnType();
$stmt->bindVariable('someValueToBeBound', '5');

$resultPool = $stmt->execute();
$result = $resultPool->getAllResults();

foreach($result as $xml) {    
    var_dump($xml->somePredicateAttribute);
}
```