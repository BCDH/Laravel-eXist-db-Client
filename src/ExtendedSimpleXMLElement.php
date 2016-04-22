<?php

namespace BCDH\ExistDbClient;

use SimpleXMLElement;
use XSLTProcessor;

class ExtendedSimpleXMLElement extends SimpleXMLElement
{
    public function transform($view)
    {
        $xsltProcessor = new XSLTProcessor();
        $xsltProcessor->registerPHPFunctions();
        $xsltProcessor->importStylesheet(simplexml_load_file($view));
        return $xsltProcessor->transformToXml($this);
    }
}
