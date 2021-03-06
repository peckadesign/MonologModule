<?php declare(strict_types = 1);

namespace Pd\MonologModule\Handlers;

/**
 * Handler exportuje zprávy do souborů, kdy pro každý den zakládá nový soubor a pro každý měsíc nový adresář
 *
 *  - Výsledná cesta je %logDir%/názevKanálu/YYYY-MM/YYYY-MM-DD-názevKanálu.log
 *  - Adresář pro uložení souboru se vytváří automaticky
 */
final class DayFileHandler extends \Monolog\Handler\AbstractProcessingHandler
{

	private string $logDir;

	private \Monolog\Formatter\LineFormatter $defaultFormatter;

	private \Monolog\Formatter\LineFormatter $priorityFormatter;

	private string $appName;

	private bool $expandNewlines;


	public function __construct(string $appName, string $logDir, bool $expandNewlines = FALSE)
	{
		parent::__construct();

		$this->logDir = $logDir;
		$this->appName = $appName;
		$this->expandNewlines = $expandNewlines;

		$this->defaultFormatter = new \Monolog\Formatter\LineFormatter('[%datetime%] %message% %context% %extra%');
		$this->priorityFormatter = new \Monolog\Formatter\LineFormatter('[%datetime%] %level_name%: %message% %context% %extra%');
	}


	/**
	 * @param array<mixed> $record
	 */
	public function handle(array $record): bool
	{
		if ($record['channel'] === $this->appName) {
			$this->setFormatter($this->defaultFormatter);
			$record['filename'] = \strtolower($record['level_name']);
		} else {
			$this->setFormatter($this->priorityFormatter);
			$record['filename'] = \strtolower($record['channel']);
		}

		return parent::handle($record);
	}


	private function getFileName(\DateTimeInterface $dateTime, string $fileName): string
	{
		$pathParts = [
			$fileName,
			$dateTime->format('Y-m'),
			$dateTime->format('Y-m-d') . '-' . $fileName,
		];

		return '/' . \implode('/', $pathParts) . '.log';
	}


	/**
	 * @param array<mixed> $record
	 */
	protected function write(array $record): void
	{
		$filePath = $this->logDir . $this->getFileName($record['datetime'], $record['filename']);
		$logDirectory = \dirname($filePath);
		\Nette\Utils\FileSystem::createDir($logDirectory);

		if ($this->expandNewlines) {
			$entry = '';
			foreach ((array) \preg_split('{[\r\n]+}', (string) $record['message']) as $line) {
				$entry .= \trim($this->getFormatter()->format(['message' => $line] + $record)) . \PHP_EOL;
			}
		} else {
			$entry = \preg_replace('#\s*\r?\n\s*#', ' ', \trim($record['formatted'])) . \PHP_EOL;
		}

		if ( ! @\file_put_contents($filePath, $entry, \FILE_APPEND | \LOCK_EX)) {
			throw new \RuntimeException(\sprintf('Unable to write to log file %s. Is directory writable?', $filePath));
		}
	}

}
