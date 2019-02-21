<?php declare(strict_types = 1);

namespace Pd\MonologModule\Handlers;

final class BlueScreenHandler extends \Monolog\Handler\AbstractProcessingHandler
{

	/**
	 * @var \Tracy\BlueScreen
	 */
	private $blueScreenRenderer;

	/**
	 * @var string
	 */
	private $logDirectory;


	public function __construct(string $logDir, $level = \Monolog\Logger::DEBUG)
	{
		parent::__construct($level, TRUE);

		$this->blueScreenRenderer = new \Tracy\BlueScreen();
		$this->logDirectory = $logDir;
	}


	private function getLogDirectory(\DateTimeInterface $dateTime)
	{
		$pathParts = [
			$this->logDirectory,
			'exception',
			$dateTime->format('Y-m'),
		];

		return \implode('/', $pathParts);
	}


	protected function write(array $record): void
	{
		if ( ! isset($record['context']['exception'])) {
			return;
		}

		$extensionLogger = new \Tracy\Logger($this->getLogDirectory($record['datetime']));

		$exception = $record['context']['exception'];
		$exceptionFile = $extensionLogger->getExceptionFile($exception);

		$this->blueScreenRenderer->renderToFile($exception, $exceptionFile);
	}

}
