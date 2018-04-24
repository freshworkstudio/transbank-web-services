<?php

namespace Freshwork\Transbank\Log;

class VoidLogger implements LoggerInterface
{
    public function log($data, $level = self::INFO, $type = null)
    {
        // Do nothing
    }
}
