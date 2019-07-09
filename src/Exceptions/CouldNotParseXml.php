<?php

namespace Metawesome\RequestXml\Exceptions;

use Exception;

class CouldNotParseXml extends Exception
{
    public static function payload($xml)
    {
        return new static($xml);
    }
}