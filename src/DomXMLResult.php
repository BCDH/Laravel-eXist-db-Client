<?php

namespace BCDH\ExistDbClient;

use XSLTProcessor;
use DOMElement;

/**
 * Simple wrapper of DOMElement
 *
 * Class DomXMLResult
 * @package BCDH\ExistDbClient
 */
class DomXMLResult
{
    /**
     * @var DOMElement
     */
    private $document;

    /**
     * DomXMLResult constructor.
     * @param DOMElement|string $documentScalar
     */
    function __construct($documentScalar)
    {
        if($documentScalar instanceof DOMElement) {
            $this->document = $documentScalar;
        } else {
            $root = simplexml_load_string($documentScalar);
            $this->document = dom_import_simplexml($root);
        }
    }

    /**
     * @param string $view XSL template file
     * @return string
     */
    public function transform($view) {
        $xsltProcessor = new XSLTProcessor();
        $xsltProcessor->registerPHPFunctions();
        $xsltProcessor->importStylesheet(simplexml_load_file($view));
        return $xsltProcessor->transformToXml($this->document->ownerDocument);
    }

    /**
     * @return DOMElement
     */
    public function getDocument() {
        return $this->document;
    }
}