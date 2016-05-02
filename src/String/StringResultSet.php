<?php

namespace BCDH\ExistDbClient\String;

use BCDH\ExistDbClient\ResultSet;

class StringResultSet extends ResultSet
{
    public function getNextResult()
    {
        $result = $this->client->retrieve(
            $this->resultId,
            $this->currentHit,
            $this->options
        );

        $this->currentHit++;
        $this->hasMoreHits = $this->currentHit < $this->hits;

        return new StringResult($result->scalar);
    }

    public function current()
    {
        return new StringResult($this->retrieve()->scalar);
    }
}