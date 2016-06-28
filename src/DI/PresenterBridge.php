<?php

namespace Pd\MonologModule\DI;

use Kdyby;
use Monolog;
use Nette;
use Pd;


class PresenterBridge
{

	/**
	 * @var Monolog\Logger
	 */
	private $logger;

	/**
	 * @var Nette\Security\User
	 */
	private $user;

	/**
	 * @var array
	 */
	private $allowedTypes;


	public function __construct(
		array $allowedTypes,
		Kdyby\Monolog\Logger $logger,
		Nette\Security\User $user
	) {
		$this->allowedTypes = $allowedTypes;
		$this->logger = $logger;
		$this->user = $user;
	}


	public function onPresenter(Nette\Application\Application $application, Nette\Application\UI\Presenter $presenter)
	{
		$success = FALSE;
		foreach ($this->allowedTypes as $allowedType) {
			$success = $presenter instanceof $allowedType;
		}

		if ( ! $success) {
			return;
		}

		$handler = new Pd\MonologModule\Handlers\FlashMessageHandler($presenter, $this->user);
		$this->logger->pushHandler($handler);
	}

}
