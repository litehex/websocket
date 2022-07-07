<?php

namespace Litehex\WebSocket;

use EasyHttp\Exceptions\WebSocketException;
use EasyHttp\WebSocket;
use EasyHttp\WebSocketConfig;
use Litehex\WebSocket\Interfaces\ConnectionInterface;
use Litehex\WebSocket\Interfaces\MessageInterface;
use Litehex\WebSocket\Interfaces\ServerEventInterface;
use Litehex\WebSocket\Traits\ConnectionTrait;
use Litehex\WebSocket\Utils\Encryption;

/**
 * WebSocketServer class
 *
 * @link    https://github.com/litehex/websocket
 * @author  Shahrad Elahi (https://github.com/shahradelahi)
 * @license https://github.com/litehex/websocket/blob/master/LICENSE (MIT License)
 */
abstract class WebSocketServer implements ServerEventInterface, MessageInterface, ConnectionInterface
{

	use ConnectionTrait;

	/**
	 * @param string $access_token
	 * @param WebSocketConfig $config
	 */
	public function __construct(private string $access_token, WebSocketConfig $config)
	{
		$this->setConfig($this->initializeConfig($config));

		$this->socket->onOpen = function (WebSocket $socket) {
			$this->passWithSafety('onOpen', $socket);
		};

		$this->socket->onClose = function (WebSocket $socket, int $closeStatus) {
			$this->passWithSafety('onClose', $socket, $closeStatus);
		};

		$this->socket->onError = function (WebSocket $socket, WebSocketException $exception) {
			$this->passWithSafety('onError', $socket, $exception);
		};

		$this->socket->onMessage = function (WebSocket $socket, string $message) {
			if (($data = $this->getServerMessage($message)) !== false) {
				match ($data['method']) {
					'ON_SUBSCRIBE' => $this->passWithSafety('onSubscribe', $socket, $data['data']['uid']),
					'ON_UNSUBSCRIBE' => $this->passWithSafety('onUnsubscribe', $socket, $data['data']['uid']),
					'ON_MESSAGE' => $this->passWithSafety('onMessage', $socket, $data['data']['uid'], $data['data']['message']),
					default => $this->passWithSafety('onError', $socket, new WebSocketException('Invalid message type.')),
				};
			} else {
				$this->onServerMessage($socket, $message);
			}
		};
	}

	private function passWithSafety(string $method, mixed ...$args): void
	{
		if (method_exists($this, $method)) {
			call_user_func_array([$this, $method], [...$args]);
		}
	}

	/**
	 * @param WebSocketConfig $config
	 * @return WebSocketConfig
	 */
	private function initializeConfig(WebSocketConfig $config): WebSocketConfig
	{
		$config->setHeaders(array_merge($config->getHeaders(), [
			'X-Authorize-With' => $this->access_token,
		]));

		return $config;
	}

	/**
	 * Check message is from the server
	 *
	 * @param string $message
	 * @return bool
	 */
	private function isFromServer(string $message): bool
	{
		if (str_starts_with($message, 'SERVER_EVENT:')) {
			$data = str_replace('SERVER_EVENT:', '', $message);
			if (Encryption::decrypt($this->access_token, $data) !== null) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Get Server message
	 *
	 * @param string $message
	 * @return array|false
	 */
	private function getServerMessage(string $message): array|false
	{
		if (!$this->isFromServer($message)) {
			return false;
		}

		$data = str_replace('SERVER_EVENT:', '', $message);
		$data = Encryption::decrypt($this->access_token, $data);
		$res = json_decode($data, true);
		return json_last_error() === JSON_ERROR_NONE ? $res : false;
	}

}