<?php declare(strict_types = 1);

namespace Pd\MonologModule\DI;

final class Extension extends \Nette\DI\CompilerExtension
{

	private $defaults = [
		'name' => '',
	];


	public function loadConfiguration()
	{
		parent::loadConfiguration();

		$containerBuilder = $this->getContainerBuilder();

		$config = $this->validateConfig($this->defaults);

		$containerBuilder
			->addDefinition($this->prefix('channelLoggerFactory'))
			->setFactory(\Pd\MonologModule\ChannelLoggerFactory::class)
		;

		$containerBuilder
			->addDefinition($this->prefix('logger'))
			->setType(\Monolog\Logger::class)
			->setFactory(\Monolog\Logger::class, ['name' => $config['name']])
		;
	}


	public function afterCompile(\Nette\PhpGenerator\ClassType $class)
	{
		$initialize = $class->getMethod('initialize');

		$initialize->addBody('$tracyLogger = new \Tracy\Bridges\Psr\PsrToTracyLoggerAdapter($this->getByType(\Psr\Log\LoggerInterface::class));');
		$initialize->addBody('\Tracy\Debugger::setLogger($tracyLogger);');
	}

}
