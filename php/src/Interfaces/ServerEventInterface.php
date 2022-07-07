<?php

namespace Litehex\WebSocket\Interfaces;

use EasyHttp\Exceptions\WebSocketException;
use EasyHttp\WebSocket;

/**
 * ServerEventInterface class
 *
 * @link    https://github.com/litehex/websocket
 * @author  Shahrad Elahi (https://github.com/shahradelahi)
 * @license https://github.com/litehex/websocket/blob/master/LICENSE (MIT License)
 */
interface ServerEventInterface
{

	/**
	 * @param WebSocket $socket
	 * @param string $uid
	 * @return void
	 * @throws WebSocketException
	 */
	public function onSubscribe(WebSocket $socket, string $uid): void;

	/**
	 * @param WebSocket $socket
	 * @param string $uid
	 * @return void
	 * @throws WebSocketException
	 */
	public function onUnsubscribe(WebSocket $socket, string $uid): void;

	/**
	 * @param WebSocket $socket
	 * @param string $message
	 * @return void
	 * @throws WebSocketException
	 */
	public function onServerMessage(WebSocket $socket, string $message): void;

}