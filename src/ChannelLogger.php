<?php declare(strict_types = 1);

namespace Pd\MonologModule;

final class ChannelLogger implements \Psr\Log\LoggerInterface
{

	private \Psr\Log\LoggerInterface $logger;

	private string $channel;


	public function __construct(\Psr\Log\LoggerInterface $logger, string $channel)
	{
		$this->logger = $logger;
		$this->channel = $channel;
	}


	/**
	 * @param array<mixed> $context
	 * @return array<mixed>
	 */
	private function getContext(array $context): array
	{
		return ['channel' => $this->channel] + $context;
	}


	/**
	 * @param string $message
	 * @param array<mixed> $context
	 */
	public function emergency($message, array $context = []): void
	{
		$this->logger->emergency($message, $this->getContext($context));
	}


	/**
	 * @param string $message
	 * @param array<mixed> $context
	 */
	public function alert($message, array $context = []): void
	{
		$this->logger->alert($message, $this->getContext($context));
	}


	/**
	 * @param string $message
	 * @param array<mixed> $context
	 */
	public function critical($message, array $context = []): void
	{
		$this->logger->critical($message, $this->getContext($context));
	}


	/**
	 * @param string $message
	 * @param array<mixed> $context
	 */
	public function error($message, array $context = []): void
	{
		$this->logger->error($message, $this->getContext($context));
	}


	/**
	 * @param string $message
	 * @param array<mixed> $context
	 */
	public function warning($message, array $context = []): void
	{
		$this->logger->warning($message, $this->getContext($context));
	}


	/**
	 * @param string $message
	 * @param array<mixed> $context
	 */
	public function notice($message, array $context = []): void
	{
		$this->logger->notice($message, $this->getContext($context));
	}


	/**
	 * @param string $message
	 * @param array<mixed> $context
	 */
	public function info($message, array $context = []): void
	{
		$this->logger->info($message, $this->getContext($context));
	}


	/**
	 * @param string $message
	 * @param array<mixed> $context
	 */
	public function debug($message, array $context = []): void
	{
		$this->logger->debug($message, $this->getContext($context));
	}


	/**
	 * @param string $message
	 * @param array<mixed> $context
	 */
	public function log($level, $message, array $context = []): void
	{
		$this->logger->log($level, $message, $this->getContext($context));
	}

}
