<?php

/**
 * Server class
 *
 * This class is a simple Echo Channel.
 *
 * @link    https://github.com/litehex/websocket
 * @author  Shahrad Elahi (https://github.com/shahradelahi)
 * @license https://github.com/litehex/websocket/blob/master/LICENSE (MIT License)
 */
class Server extends \Litehex\WebSocket\WebSocketServer
{

	/**
	 * This property is used to store all the users connected to the server.
	 *
	 * @var array $clients
	 */
	public array $clients = [];

	/**
	 * This property is used to store all the messages sent by the users.
	 * (It's unnecessary, but it's a good practice)
	 *
	 * @var array
	 */
	public array $messages = [];

	/**
	 * After you connect to the LiteSocket server, you can send a message to the server.
	 *
	 * @param EasyHttp\WebSocket $socket
	 * @return void
	 * @throws \Exception
	 */
	public function onOpen(EasyHttp\WebSocket $socket): void
	{
		echo __CLASS__ . '::' . __FUNCTION__ . '()' . '<br>';
	}

	/**
	 * After you disconnect from the LiteSocket server, you can send a message to the server.
	 *
	 * Please note that when was connected to LiteSocket server, and after you got disconnected this
	 * method will be called
	 *
	 * @param EasyHttp\WebSocket $socket
	 * @param int $closeStatus
	 * @return void
	 */
	public function onClose(EasyHttp\WebSocket $socket, int $closeStatus): void
	{
		echo __CLASS__ . '::' . __FUNCTION__ . '(' . $closeStatus . ')' . '<br>';
	}

	/**
	 * When you get an error, you can see or handle them right here.
	 *
	 * @param EasyHttp\WebSocket $socket
	 * @param EasyHttp\Exceptions\WebSocketException $exception
	 * @return void
	 */
	public function onError(EasyHttp\WebSocket $socket, EasyHttp\Exceptions\WebSocketException $exception): void
	{
		echo $exception->getMessage() . '<br>';
	}

	/**
	 * After a user subscribes to your channel, you can add him to the list of users,
	 * and you may send a warm welcome message to him.
	 *
	 * @param \EasyHttp\WebSocket $socket
	 * @param string $uid
	 * @return void
	 * @throws Exception
	 */
	public function onSubscribe(EasyHttp\WebSocket $socket, string $uid): void
	{
		// Save the user to the list of users.
		$this->clients[] = $uid;

		// Send a message to the user.
		$socket->send(json_encode([
			'method' => 'SEND_MESSAGE',
			'uid' => $uid,
			'message' => 'Welcome to Echo Room!',
		]));
	}

	/**
	 * After someone subscribed to your channel, you will notify by this method.
	 * You can use this method to remove the user from your list.
	 *
	 * @param EasyHttp\WebSocket $socket
	 * @param string $uid
	 * @return void
	 */
	public function onUnsubscribe(EasyHttp\WebSocket $socket, string $uid): void
	{
		// Remove the user from the list of users.
		$this->clients = array_diff($this->clients, [$uid]);
	}

	/**
	 * On this method you have to handle messages from server.
	 * Messages such as: AUTHENTICATE, MESSAGE_SENT, etc. and you can handle them here.
	 *
	 * @param \EasyHttp\WebSocket $socket
	 * @param string $message
	 * @return void
	 */
	public function onServerMessage(EasyHttp\WebSocket $socket, string $message): void
	{
		// Write your code here
	}

	/**
	 * On this method you have to handle messages from users.
	 * Messages are decrypted, and you need to handle them here.
	 *
	 * @param \EasyHttp\WebSocket $socket
	 * @param string $uid
	 * @param string $message
	 * @return void
	 * @throws Exception
	 */
	public function onMessage(EasyHttp\WebSocket $socket, string $uid, string $message): void
	{
		// This is an Echo Server. So, you have to send the message back to the user.
		$socket->send(json_encode([
			'method' => 'SEND_MESSAGE',
			'uid' => $uid,
			'message' => 'You said: ' . $message,
		]));

		// Or do more things with the message.
		if ($message === 'ping') {
			$socket->send(json_encode([
				'method' => 'SEND_MESSAGE',
				'uid' => $uid,
				'message' => 'pong!',
			]));
		}

		// Save the message to the list of messages.
		$this->messages[] = [
			'uid' => $uid,
			'message' => $message,
		];
	}

}
