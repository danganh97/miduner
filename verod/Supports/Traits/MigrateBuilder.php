<?php

namespace Midun\Supports\Traits;

use Midun\Database\DatabaseBuilder\Compile;
use Midun\Database\DatabaseBuilder\ColumnBuilder;

trait MigrateBuilder
{
    /**
     * Create table
     * 
     * @param string $table 
     * @param \Closure $closure
     * 
     * @return void
     */
    public function createMigrate($table, \Closure $closure)
    {
        try {
            $columnBuilder = new ColumnBuilder;
            $closure($columnBuilder);
            $sqlBuildings = (new Compile)->exec($columnBuilder->columns());
            $createTableSql = "CREATE TABLE $table (";
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
            app()->make(\Midun\Supports\ConsoleOutput::class)->printError($e->getMessage());
            exit(1);
        }
    }

    /**
     * Create if not exists table
     * 
     * @param string $table
     * @param \Closure $closure
     * 
     * @return void
     */
    public function createIfNotExistsMigrate($table, $closure)
    {
        try {
            $columnBuilder = new ColumnBuilder;
            $closure($columnBuilder);
            $sqlBuildings = (new Compile)->exec($columnBuilder->columns());
            $createTableSql = "CREATE TABLE IF NOT EXISTS $table (";
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
            app()->make(\Midun\Supports\ConsoleOutput::class)->printError($e->getMessage());
            exit(1);
        }
    }

    /**
     * Execute migrate table
     * 
     * @param string $table
     * 
     * @return void
     */
    public function dropMigrate($table)
    {
        try {
            $dropTableSql = "DROP TABLE $table";
            app()->make('connection')->getConnection()->query($dropTableSql);
        } catch (\PDOException $e) {
            app()->make(\Midun\Supports\ConsoleOutput::class)->printError($e->getMessage());
            exit(1);
        }
    }

    /**
     * Execute drop if exists table
     * 
     * @param string $table
     * 
     * @return void
     */
    public function dropIfExistsMigrate($table)
    {
        try {
            $dropTableSql = "DROP TABLE IF EXISTS $table";
            app()->make('connection')->getConnection()->query($dropTableSql);
        } catch (\PDOException $e) {
            app()->make(\Midun\Supports\ConsoleOutput::class)->printError($e->getMessage());
            exit(1);
        }
    }

    /**
     * Execute truncate table
     * 
     * @param string $table
     * 
     * @return void
     */
    public function truncateMigrate($table)
    {
        try {
            $dropTableSql = "TRUNCATE $table";
            app()->make('connection')->getConnection()->query($dropTableSql);
        } catch (\PDOException $e) {
            app()->make(\Midun\Supports\ConsoleOutput::class)->printError($e->getMessage());
            exit(1);
        }
    }

    /**
     * Set table migration
     * 
     * @param string $table
     * 
     * @param ColumnBuilder $columns
     * 
     * @return void
     */
    public function tableMigrate($table, ColumnBuilder $columns)
    {
        $this->table = $table;
        $this->columns = $columns;
    }
}
