<?php

namespace Litehex\WebSocket\Interfaces;

use EasyHttp\Exceptions\WebSocketException;
use EasyHttp\WebSocket;

/**
 * MessageInterface class
 *
 * @link    https://github.com/litehex/websocket
 * @author  Shahrad Elahi (https://github.com/shahradelahi)
 * @license https://github.com/litehex/websocket/blob/master/LICENSE (MIT License)
 */
interface MessageInterface
{

	/**
	 * @param WebSocket $socket
	 * @param string $uid
	 * @param string $message
	 * @return void
	 * @throws WebSocketException
	 */
	public function onMessage(WebSocket $socket, string $uid, string $message): void;

}