<?php

namespace BCDH\ExistDbClient\String;

use BCDH\ExistDbClient\ResultInterface;

class StringResult implements ResultInterface
{
    /** @var string  */
    private $document;

    function __construct($documentScalar)
    {
        $this->document = $documentScalar;
    }

    /**
     * @return string
     */
    public function getDocument() {
        return $this->document;
    }
}