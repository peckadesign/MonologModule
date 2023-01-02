<?php declare(strict_types = 1);

namespace Pd\MonologModule\Handlers;

/**
 * Handler exportuje zprávy do souborů, kdy pro každý den zakládá nový soubor a pro každý měsíc nový adresář
 *
 *  - Výsledná cesta je %logDir%/názevKanálu/YYYY-MM/YYYY-MM-DD-názevKanálu.log
 *  - Adresář pro uložení souboru se vytváří automaticky
 *
 * @phpstan-import-type FormattedRecord from \Monolog\Handler\AbstractProcessingHandler
 */
final class DayFileHandler extends \Monolog\Handler\AbstractProcessingHandler
{

	private string $logDir;

	private \Monolog\Formatter\LineFormatter $defaultFormatter;

	private \Monolog\Formatter\LineFormatter $priorityFormatter;

	private string $appName;


	public function __construct(string $appName, string $logDir)
	{
		parent::__construct();

		$this->logDir = $logDir;
		$this->appName = $appName;

		$this->defaultFormatter = new \Monolog\Formatter\LineFormatter('[%datetime%] %message% %context% %extra%');
		$this->priorityFormatter = new \Monolog\Formatter\LineFormatter('[%datetime%] %level_name%: %message% %context% %extra%');
	}


	public function handle(array $record): bool
	{
		if ($record['channel'] === $this->appName) {
			$this->setFormatter($this->defaultFormatter);
		} else {
			$this->setFormatter($this->priorityFormatter);
		}

		return parent::handle($record);
	}


	/**
	 * @param array<mixed> $record
	 */
	private function getFileName(array $record): string
	{
		return $record['channel'] === $this->appName ? $record['level_name'] : $record['channel'];
	}


	private function getFilePath(\DateTimeInterface $dateTime, string $fileName): string
	{
		return \sprintf('%s/%s/%s/%s-%s.log',
			$this->logDir,
			$fileName,
			$dateTime->format('Y-m'),
			$dateTime->format('Y-m-d'),
			$fileName
		);
	}


	/**
	 * @param array<mixed> $record
	 *
	 * @phpstan-param FormattedRecord $record
	 */
	protected function write(array $record): void
	{
		$filePath = $this->getFilePath($record['datetime'], $this->getFileName($record));
		$logDirectory = \dirname($filePath);
		\Nette\Utils\FileSystem::createDir($logDirectory);

		$entry = \preg_replace('#\s*\r?\n\s*#', ' ', \trim($record['formatted'])) . \PHP_EOL;

		if ( ! @\file_put_contents($filePath, $entry, \FILE_APPEND | \LOCK_EX)) {
			throw new \RuntimeException(\sprintf('Unable to write to log file %s. Is directory writable?', $filePath));
		}
	}

}
