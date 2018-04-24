<?php

namespace Freshwork\Transbank\Log;

interface LoggerInterface
{
    const LEVEL_ERROR = 'error';
    const LEVEL_WARNING = 'warning';
    const LEVEL_INFO = 'info';

    public function log($data, $level = self::LEVEL_INFO, $type = null);
}
