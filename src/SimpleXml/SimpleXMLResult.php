<?php

namespace BCDH\ExistDbClient\SimpleXml;

use BCDH\ExistDbClient\ResultInterface;
use XSLTProcessor;

class SimpleXMLResult implements ResultInterface
{
    /**
     * @var SimpleXMLElement
     */
    private $document;

    function __construct($documentScalar)
    {
        $this->document = new SimpleXMLElement($documentScalar);
    }

    public function transform($view) {
        $xsltProcessor = new XSLTProcessor();
        $xsltProcessor->registerPHPFunctions();
        $xsltProcessor->importStylesheet(simplexml_load_file($view));
        return $xsltProcessor->transformToXml($this->document);
    }

    /**
     * @return SimpleXMLElement
     */
    public function getDocument() {
        return $this->document;
    }
}