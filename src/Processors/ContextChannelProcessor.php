<?php declare(strict_types = 1);

namespace Pd\MonologModule\Processors;

final class ContextChannelProcessor implements \Monolog\Processor\ProcessorInterface
{

	/**
	 * @param array<mixed> $record
	 * @return array<mixed>
	 */
	public function __invoke(array $record): array
	{
		if (isset($record['context']['channel'])) {
			$record['channel'] = $record['context']['channel'];
			unset($record['context']['channel']);
		}

		return $record;
	}

}
