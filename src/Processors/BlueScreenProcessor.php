<?php declare(strict_types = 1);

namespace Pd\MonologModule\Processors;

final class BlueScreenProcessor
{

	private \Tracy\BlueScreen $blueScreenRenderer;

	private string $logDirectory;


	public function __construct(string $logDir)
	{
		$this->blueScreenRenderer = new \Tracy\BlueScreen();
		$this->logDirectory = $logDir;
	}


	private function getLogDirectory(\DateTimeInterface $dateTime): string
	{
		$pathParts = [
			$this->logDirectory,
			'exception',
			$dateTime->format('Y-m'),
		];

		return \implode('/', $pathParts);
	}


	/**
	 * @param array<mixed> $record
	 * @return array<mixed>
	 */
	public function __invoke(array $record): array
	{
		$exception = $record['context']['exception'] ?? NULL;

		if ( ! $exception instanceof \Throwable) {
			return $record;
		}

		$dateTime = $record['datetime'] ?? new \DateTimeImmutable();

		$logDirectory = $this->getLogDirectory($dateTime);
		\Nette\Utils\FileSystem::createDir($logDirectory);

		$extensionLogger = new \Tracy\Logger($logDirectory);

		$exceptionFile = $extensionLogger->getExceptionFile($exception);
		$record['context']['exceptionFile'] = $exceptionFile;

		$this->blueScreenRenderer->renderToFile($exception, $exceptionFile);

		return $record;
	}

}
