<?php declare(strict_types = 1);

namespace PdTests\MonologModule\DI;

require __DIR__ . '/../bootstrap.php';

class ExtensionTest extends \Tester\TestCase
{

	public function testExtension()
	{
		$configurator = new \Nette\Configurator();
		$configurator->addConfig(__DIR__ . '/extension.neon');
		$configurator->setTempDirectory(__DIR__);
		$container = $configurator->createContainer();

		/** @var \Monolog\Logger $logger */
		$logger = $container->getByType(\Psr\Log\LoggerInterface::class);

		\Tester\Assert::equal(\Monolog\Logger::class, \get_class($logger));

		\Tester\Assert::truthy($container->getByType(\Pd\MonologModule\ChannelLoggerFactory::class));

		$processors = array_map('get_class', $logger->getProcessors());
		if ( ! \in_array(\Pd\MonologModule\Processors\ContextChannelProcessor::class, $processors)) {
			\Tester\Assert::fail('Monolog neobsahuje  procesor');
		}
	}
}

(new ExtensionTest())->run();
