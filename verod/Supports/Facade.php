<?php

namespace Midun\Supports;

use Midun\Http\Exceptions\AppException;

abstract class Facade
{
	/**
	 * Get facade of entity
	 *
	 * @return string
	 *
	 * @throws AppException
	 */
	protected static function getFacadeAccessor()
	{
		throw new AppException("Method " . __METHOD__ . " is not overide.");
	}

	/**
	 * Call static handler
	 *
	 * @param string $method
	 * @param array $arguments
	 *
	 * @return mixed|object
	 *
	 * @throws AppException
	 */
	public static function __callStatic($method, $arguments)
	{
		return app()->make(static::getFacadeAccessor())->$method(...$arguments);
	}

	/**
	 * Call handler
	 *
	 * @param string $method
	 * @param array $arguments
	 *
	 * @return mixed|object
	 * 
	 * @throws AppException
	 */
	public function __call($method, $arguments)
	{
		return app()->make(static::getFacadeAccessor())->$method(...$arguments);
	}
}
