<?php

namespace Main\Supports\Traits;

use Main\Colors;
use Main\Database\DatabaseBuilder\Compile;
use Main\Database\DatabaseBuilder\ColumnBuilder;

trait MigrateBuilder
{
    public function createMigrate($table, $columns)
    {
        try {
            $columns = call_user_func_array($columns, [new ColumnBuilder]);
            $sqlBuildings = (new Compile)->exec($columns);
            $database = config('database.connection.database');
            $createTableSql = "CREATE TABLE $database.$table (";
            foreach ($sqlBuildings as $key => $sql) {
                if ($key == count($sqlBuildings) - 1) {
                    $createTableSql .= "$sql";
                } else {
                    $createTableSql .= "$sql, ";
                }
            }
            $createTableSql .= ');';
            app()->make('connection')->getConnection()->query($createTableSql);
        } catch (\PDOException $e) {
            (new Colors)->printError($e->getMessage());
        }
    }

    public function createIfNotExistsMigrate($table, $columns)
    {
        try {
            $columns = call_user_func_array($columns, [new ColumnBuilder]);
            $sqlBuildings = (new Compile)->exec($columns);
            $database = config('database.connection.database');
            $createTableSql = "CREATE TABLE IF NOT EXISTS $database.$table (";
            foreach ($sqlBuildings as $key => $sql) {
                if ($key == count($sqlBuildings) - 1) {
                    $createTableSql .= "$sql";
                } else {
                    $createTableSql .= "$sql, ";
                }
            }
            $createTableSql .= ');';
            app()->make('connection')->getConnection()->query($createTableSql);
        } catch (\PDOException $e) {
            (new Colors)->printError($e->getMessage());
        }
    }

    public function dropMigrate($table)
    {
        try {
            $dropTableSql = "DROP TABLE $table";
            app()->make('connection')->getConnection()->query($dropTableSql);
        } catch (\PDOException $e) {
            (new Colors)->printError($e->getMessage());
        }
    }

    public function dropIfExistsMigrate($table)
    {
        try {
            $dropTableSql = "DROP TABLE IF EXISTS $table";
            app()->make('connection')->getConnection()->query($dropTableSql);
        } catch (\PDOException $e) {
            (new Colors)->printError($e->getMessage());
        }
    }

    public function truncateMigrate($table)
    {
        try {
            $dropTableSql = "TRUNCATE $table";
            app()->make('connection')->getConnection()->query($dropTableSql);
        } catch (\PDOException $e) {
            (new Colors)->printError($e->getMessage());
        }
    }

    public function tableMigrate($table, ColumnBuilder $columns)
    {
        $this->table = $table;
        $this->columns = $columns;
    }
}
