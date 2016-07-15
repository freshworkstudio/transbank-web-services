<?php

namespace Freshwork\Transbank\Log;


class LogHandler
{
	public static function log($string, $level = LoggerInterface::INFO, $type = null)
	{
		LoggerFactory::logger()->log($string, $level, $type);
	}
}