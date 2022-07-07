<?php

namespace Litehex\WebSocket\Tests;

use Dotenv\Dotenv;
use Litehex\WebSocket\Utils\Encryption;
use PHPUnit\Framework\TestCase;

class WebSocketServerTest extends TestCase
{

	private string $access_token;

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

	public function test_receiving_message(): void
	{
		$dotEnv = Dotenv::createImmutable(__DIR__ . '/../');
		$dotEnv->load();

		$this->access_token = $_ENV['ACCESS_TOKEN'];
		$message = "SERVER_EVENT:bDAvNFljZE9sbWJXaFIrNlhtL1RDbnh1OTY0d0c0aC9yME1LRHB4NUJDWFlxYUNlaEFCcjY0cjVwNHBFbEJFZkFyUWU2dk9MYkc0VVBFdHhIdkJGalZybmNjM0tuVVdObUhEelpyNEgvY1k9";


		$this->assertTrue($this->isFromServer($message));
		$this->assertEquals(
			'{"method":"ON_MESSAGE","data":{"uid":"0331648b-5a35","message":"Hi"}}',
			json_encode($this->getServerMessage($message))
		);

		echo json_encode($this->getServerMessage($message), JSON_PRETTY_PRINT);
	}


}
