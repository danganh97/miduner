<?php

namespace Midun\Database\DatabaseBuilder;

use Midun\Supports\Traits\MigrateBuilder;

class Schema
{
	use MigrateBuilder;

	/**
	 * Handle call static
	 *
	 * @param string $method
	 * @param array $arguments
	 *
	 * @return void
	 *
	 * @throws DatabaseBuilderException
	 */
	public static function __callStatic(string $method, array $arguments)
	{
		switch($method) {
			case 'create':
				list($table, $columns) = $arguments;
				(new self)->createMigrate($table, $columns);
				break;
			case 'createIfNotExists':
				list($table, $columns) = $arguments;
				(new self)->createIfNotExistsMigrate($table, $columns);
				break;
			case 'drop':
				list($table) = $arguments;
				(new self)->dropMigrate($table);
				break;
			case 'dropIfExists':
				list($table) = $arguments;
				(new self)->dropIfExistsMigrate($table);
				break;
			case 'truncate':
				list($table) = $arguments;
				(new self)->truncateMigrate($table);
				break;
			default:
				throw new DatabaseBuilderException("Method '$method' is not supported.");
		}
	}
}
