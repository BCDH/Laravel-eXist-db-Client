<?php

namespace BCDH\ExistDbClient;

interface ResultInterface {
    /**
     * Get actual XML content displayed as DOM|Object|string
     *
     * @return mixed
     */
    public function getDocument();
}