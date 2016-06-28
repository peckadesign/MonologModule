<?php

namespace Pd\MonologModule\DI;

use Nette;
use Pd;


class Extension extends Nette\DI\CompilerExtension
{

	private $defaults = [
		'allowedTypes' => [
		],
	];

	public function beforeCompile()
	{
		$containerBuilder = $this->getContainerBuilder();

		$config = $this->validateConfig($this->defaults);

		$presenterBridge = $containerBuilder
			->addDefinition($this->prefix('presenterBridge'))
			->setClass(PresenterBridge::class, [$config['allowedTypes']])
		;

		$application = $containerBuilder->getDefinition($containerBuilder->getByType(Nette\Application\Application::class));
		$application->addSetup('?->onPresenter[] = ?', ['@self', [$presenterBridge, 'onPresenter']]);
	}

}
