<?php

namespace Litehex\WebSocket\Utils;

/**
 * Encryption class
 *
 * @link    https://github.com/litehex/websocket
 * @author  Shahrad Elahi (https://github.com/shahradelahi)
 * @license https://github.com/litehex/websocket/blob/master/LICENSE (MIT License)
 */
class Encryption
{

	/**
	 * @param string $secret
	 * @param string $input
	 * @param string $cipher
	 *
	 * @return string
	 */
	public static function encrypt(string $secret, string $input, string $cipher = 'AES-256-CBC'): string
	{
		return base64_encode(openssl_encrypt($input, $cipher, $secret, 0, substr($secret, 0, 16)));
	}

	/**
	 * @param string $secret
	 * @param string $input
	 * @param string $cipher
	 *
	 * @return string
	 */
	public static function decrypt(string $secret, string $input, string $cipher = 'AES-256-CBC'): string
	{
		return openssl_decrypt(base64_decode($input), $cipher, $secret, 0, substr($secret, 0, 16));
	}

}