<?php declare(strict_types = 1);

namespace Pd\MonologModule;

final class ChannelLogger implements \Psr\Log\LoggerInterface
{

	/**
	 * @var \Psr\Log\LoggerInterface
	 */
	private $logger;

	/**
	 * @var string
	 */
	private $channel;


	public function __construct(\Psr\Log\LoggerInterface $logger, string $channel)
	{
		$this->logger = $logger;
		$this->channel = $channel;
	}


	private function getContext(array $context): array
	{
		return ['channel' => $this->channel] + $context;
	}


	public function emergency($message, array $context = [])
	{
		$this->logger->emergency($message, $this->getContext($context));
	}


	public function alert($message, array $context = [])
	{
		$this->logger->alert($message, $this->getContext($context));
	}


	public function critical($message, array $context = [])
	{
		$this->logger->critical($message, $this->getContext($context));
	}


	public function error($message, array $context = [])
	{
		$this->logger->error($message, $this->getContext($context));
	}


	public function warning($message, array $context = [])
	{
		$this->logger->warning($message, $this->getContext($context));
	}


	public function notice($message, array $context = [])
	{
		$this->logger->notice($message, $this->getContext($context));
	}


	public function info($message, array $context = [])
	{
		$this->logger->info($message, $this->getContext($context));
	}


	public function debug($message, array $context = [])
	{
		$this->logger->debug($message, $this->getContext($context));
	}


	public function log($level, $message, array $context = [])
	{
		$this->logger->log($message, $this->getContext($context));
	}

}
