<?php

namespace Litehex\WebSocket\Interfaces;

use EasyHttp\Exceptions\WebSocketException;
use EasyHttp\WebSocket;

/**
 * ConnectionInterface class
 *
 * @link    https://github.com/litehex/websocket
 * @author  Shahrad Elahi (https://github.com/shahradelahi)
 * @license https://github.com/litehex/websocket/blob/master/LICENSE (MIT License)
 */
interface ConnectionInterface
{

	/**
	 * @param WebSocket $socket
	 * @return void
	 * @throws WebSocketException
	 */
	public function onOpen(WebSocket $socket): void;

	/**
	 * @param WebSocket $socket
	 * @param int $closeStatus
	 * @return void
	 * @throws WebSocketException
	 */
	public function onClose(WebSocket $socket, int $closeStatus): void;

	/**
	 * @param WebSocket $socket
	 * @param WebSocketException $exception
	 * @return void
	 */
	public function onError(WebSocket $socket, WebSocketException $exception): void;

}