<?php declare(strict_types = 1);

namespace Pd\MonologModule;

final class ChannelLoggerFactory
{

	/**
	 * @var \Psr\Log\LoggerInterface
	 */
	private $logger;


	public function __construct(\Psr\Log\LoggerInterface $logger)
	{
		$this->logger = $logger;
	}


	public function create(string $channel): \Psr\Log\LoggerInterface
	{
		$logger = new ChannelLogger($this->logger, $channel);

		return $logger;
	}

}
