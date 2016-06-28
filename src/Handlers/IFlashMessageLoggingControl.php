<?php

namespace Pd\MonologModule\Handlers;

use Monolog;

interface IFlashMessageLoggingControl
{

	/**
	 * @return Monolog\Logger
	 */
	public function getLogger();
	
}
