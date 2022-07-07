<?php

namespace Litehex\WebSocket\Utils;

/**
 * Toolkit class
 *
 * @link    https://github.com/litehex/websocket
 * @author  Shahrad Elahi (https://github.com/shahradelahi)
 * @license https://github.com/litehex/websocket/blob/master/LICENSE (MIT License)
 */
class Toolkit
{

	/**
	 * Check the given data is a valid JSON string.
	 *
	 * @param mixed $data
	 * @return bool
	 */
	public static function isJson(mixed $data): bool
	{
		if (is_string($data)) {
			json_decode($data);
			return (json_last_error() === JSON_ERROR_NONE);
		}

		return false;
	}

}