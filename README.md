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
- PHP XSLT extension
```bash
sudo apt-get install php5-xsl
```

## Installing

####1. Add the service provider to your config/app.php:

    BCDH\ExistDbClient\ExistDbServiceProvider::class

####2. Publish your configuration file:
    
    php artisan vendor:publish
    
####3. Edit your connection credentials in `config/exist-db.php`

    [
        'protocol'  => "http",
        'user'      => "admin",
        'password'  => "admin",
        'host'      => "localhost",
        'port'      => 8080,
        'path'      => "/exist/xmlrpc/",
    ]
    

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

## Return types

- Query::setStringReturnType()
    result is instance of [DOMElement](http://php.net/manual/en/class.domelement.php)

- Query::setSimpleXMLReturnType()
    result is instance of [SimpleXMLElement](http://php.net/manual/en/class.simplexmlelement.php)

- Query::setDomXMLReturnType()
    result is string

#### Get result field

- DomXmlResult
```php
$document = $result->getDocument();
$title = $doc->getElementsByTagName('TITLE')->item(0)->nodeValue;
```

- SimpleXML
```php
$document = $result->getDocument();
$title = $doc->TITLE;
```

#### Get result attribute

- DomXmlResult
```php
$document = $result->getDocument();
$isFavorite = $doc->hasAttribute('favourite');
```

- SimpleXML
```php
$document = $result->getDocument();
$attributes = $document->attributes();
$isFavorite = isset($attributes['favourite']);
```

#### XLS Transformations

- Single result (DomXmlResult|SimpleXmlResult)

```php
$resultPool = $stmt->execute();
$results = $resultPool->getAllResults();
$res = $results[0];

$html = $res->transform(__DIR__.'/xml/cd_catalog_simplified.xsl');
```

- ResultSet

```php
$resultPool = $stmt->execute();
$results = $resultPool->getAllResults();
$rootTagName = 'catalog';

$html = $resultPool->transform($rootTagName, $results, __DIR__.'/xml/cd_catalog_simplified.xsl');
```