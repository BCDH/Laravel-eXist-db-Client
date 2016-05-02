<?php

namespace BCDH\ExistDbClient\SimpleXml;

use XSLTProcessor;

class SimpleXMLElement extends \SimpleXMLElement
{
    public function transform($view)
    {
        $xsltProcessor = new XSLTProcessor();
        $xsltProcessor->registerPHPFunctions();
        $xsltProcessor->importStylesheet(simplexml_load_file($view));
        return $xsltProcessor->transformToXml($this);
    }
}
