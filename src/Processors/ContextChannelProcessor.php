<?php declare(strict_types = 1);

namespace Pd\MonologModule\Processors;

final class ContextChannelProcessor
{

	public function __invoke($record)
	{
		if (isset($record['context']['channel'])) {
			$record['channel'] = $record['context']['channel'];
			unset($record['context']['channel']);
		}

		return $record;
	}

}
