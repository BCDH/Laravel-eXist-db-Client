<?php

namespace BCDH\ExistDbClient;

use XSLTProcessor;

class ExtendedXMLResult
{
    /**
     * @var ExtendedSimpleXMLElement
     */
    private $document;

    function __construct($documentScalar)
    {
        $this->document = new ExtendedSimpleXMLElement($documentScalar);
    }

    public function transform($view) {
        $xsltProcessor = new XSLTProcessor();
        $xsltProcessor->registerPHPFunctions();
        $xsltProcessor->importStylesheet(simplexml_load_file($view));
        return $xsltProcessor->transformToXml($this->document);
    }
}