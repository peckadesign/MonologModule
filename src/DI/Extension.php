<?php declare(strict_types = 1);

namespace Pd\MonologModule\DI;

final class Extension extends \Nette\DI\CompilerExtension
{

	/**
	 * @var array<string, mixed>
	 */
	private array $defaults = [
		'name' => '',
	];


	public function loadConfiguration(): void
	{
		parent::loadConfiguration();

		$containerBuilder = $this->getContainerBuilder();

		$config = $this->validateConfig($this->defaults);

		$containerBuilder
			->addDefinition($this->prefix('channelLoggerFactory'))
			->setFactory(\Pd\MonologModule\ChannelLoggerFactory::class)
		;

		$containerBuilder
			->addDefinition($this->prefix('contextChannelProcess'))
			->setType(\Pd\MonologModule\Processors\ContextChannelProcessor::class)
			->setFactory(\Pd\MonologModule\Processors\ContextChannelProcessor::class)
		;

		$containerBuilder
			->addDefinition($this->prefix('logger'))
			->setType(\Monolog\Logger::class)
			->setFactory(\Monolog\Logger::class, ['name' => $config['name']])
			->addSetup(new \Nette\DI\Statement('$service->pushProcessor(?)', ['@' . \Pd\MonologModule\Processors\ContextChannelProcessor::class]))
		;
	}


	public function afterCompile(\Nette\PhpGenerator\ClassType $class): void
	{
		$initialize = $class->getMethod('initialize');

		$initialize->addBody('$tracyLogger = new \Pd\MonologModule\Tracy\PsrToTracyLoggerAdapter($this->getByType(\Psr\Log\LoggerInterface::class));');
		$initialize->addBody('\Tracy\Debugger::setLogger($tracyLogger);');
	}

}
