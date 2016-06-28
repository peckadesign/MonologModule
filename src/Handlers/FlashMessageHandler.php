<?php

namespace Pd\MonologModule\Handlers;

use Kdyby;
use Monolog;
use Nette;
use Pd;


class FlashMessageHandler extends Monolog\Handler\AbstractProcessingHandler
{

	/**
	 * @var Nette\Application\UI\Control
	 */
	private $control;

	/**
	 * @var Monolog\Formatter\LineFormatter
	 */
	private $formater;

	/**
	 * @var Nette\Security\User
	 */
	private $user;


	public function __construct(Nette\Application\UI\Control $control, Nette\Security\User $user)
	{
		$this->control = $control;
		$this->formater = new Monolog\Formatter\LineFormatter('%datetime%: %message%');
		$this->setFormatter($this->formater);
		$this->level = Monolog\Logger::DEBUG;
		$this->user = $user;
	}


	public function isHandling(array $record)
	{
		$return = parent::isHandling($record);

		if (
			$return
			&&
			$record['level'] === Monolog\Logger::DEBUG
			&&
			! $this->user->isInRole(Pd\User\Acl::ROLE_DEVELOPER)
		) {
			$return = FALSE;
		}

		return $return;
	}


	protected function write(array $record)
	{
		if ($record['level'] > Monolog\Logger::WARNING) {
			$level = Pd\Controls\FlashMessageControl::STATUS_ERROR;
		} else {
			$level = Pd\Controls\FlashMessageControl::STATUS_INFO;
		}

		$this->control->flashMessage($record['formatted'], $level);
	}
}
