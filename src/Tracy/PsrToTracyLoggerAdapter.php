<?php declare(strict_types = 1);

namespace Pd\MonologModule\Tracy;

class PsrToTracyLoggerAdapter implements \Tracy\ILogger
{
	/** Tracy logger level to PSR-3 log level mapping */
	private const LEVEL_MAP = [
		\Tracy\ILogger::DEBUG => \Psr\Log\LogLevel::DEBUG,
		\Tracy\ILogger::INFO => \Psr\Log\LogLevel::INFO,
		\Tracy\ILogger::WARNING => \Psr\Log\LogLevel::WARNING,
		\Tracy\ILogger::ERROR => \Psr\Log\LogLevel::ERROR,
		\Tracy\ILogger::EXCEPTION => \Psr\Log\LogLevel::ERROR,
		\Tracy\ILogger::CRITICAL => \Psr\Log\LogLevel::CRITICAL,
	];

	/** @var \Psr\Log\LoggerInterface */
	private $psrLogger;


	public function __construct(\Psr\Log\LoggerInterface $psrLogger)
	{
		$this->psrLogger = $psrLogger;
	}


	public function log($value, $level = self::INFO)
	{
		if ($value instanceof \Throwable) {
			$message = \Tracy\Helpers::getClass($value) . ': ' . $value->getMessage() . ($value->getCode() ? ' #' . $value->getCode() : '') . ' in ' . $value->getFile() . ':' . $value->getLine();
			$context = ['exception' => $value];

		} elseif ( ! \is_string($value)) {
			$message = \trim(\Tracy\Dumper::toText($value));
			$context = [];

		} else {
			$message = $value;
			$context = [];
		}

		if ( ! isset(self::LEVEL_MAP[$level])) {
			$context['originalLevel'] = $level;

			$level = \Tracy\ILogger::INFO;
		}

		$this->psrLogger->log(
			self::LEVEL_MAP[$level],
			$message,
			$context
		);
	}

}
