<?php

namespace Litehex\WebSocket\Traits;

use EasyHttp\WebSocket;
use EasyHttp\WebSocketConfig;

/**
 * ConnectionTrait class
 *
 * @link    https://github.com/litehex/websocket
 * @author  Shahrad Elahi (https://github.com/shahradelahi)
 * @license https://github.com/litehex/websocket/blob/master/LICENSE (MIT License)
 */
trait ConnectionTrait
{

	/**
	 * @var WebSocket|null
	 */
	protected ?WebSocket $socket = null;

	/**
	 * @var WebSocketConfig|null
	 */
	protected ?WebSocketConfig $config = null;

	/**
	 * @var string
	 */
	public string $socket_url = 'wss://socket.litehex.com/';

	/**
	 * @param WebSocketConfig|null $config
	 */
	protected function setConfig(?WebSocketConfig $config): void
	{
		$this->config = $config;
		$this->socket = new WebSocket();
	}

	/**
	 * @return void
	 */
	public function connect(): void
	{
		$this->socket->connect($this->socket_url, $this->config);
	}

	/**
	 * isConnected
	 *
	 * @return bool
	 */
	public function isConnected(): bool
	{
		return $this->socket->isConnected();
	}

}