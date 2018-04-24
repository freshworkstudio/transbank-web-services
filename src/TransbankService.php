<?php

namespace Freshwork\Transbank;

use Freshwork\Transbank\Log\LoggerInterface;

abstract class TransbankService
{
    public function debug(LoggerInterface $logger = null)
    {
        $this->service->setLogger($logger);
    }
}
