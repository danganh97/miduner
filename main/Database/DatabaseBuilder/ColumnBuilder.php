<?php

namespace Main\Database\DatabaseBuilder;

class ColumnBuilder
{
    public $columns = [];
    public $name;
    public $length;
    public $dataType;
    public $nullable = false;
    public $unsigned = false;
    public $pk = false;
    public $timestamps = false;
    public $autoIncrement = false;
    public $default = '';
    public $comment = '';
    public $unique = false;
    public $foreignKey;
    public $references;
    public $on;
    public $onUpdate;
    public $onDelete;

    public function reset()
    {
        $this->name = '';
        $this->length = null;
        $this->dataType = null;
        $this->nullable = null;
        $this->pk = false;
        $this->timestamps = false;
        $this->unsigned = false;
        $this->autoIncrement = false;
        $this->default = '';
        $this->comment = '';
        $this->unique = false;
        $this->foreignKey = null;
        $this->references = null;
        $this->on = null;
        $this->onUpdate = null;
        $this->onDelete = null;
    }

    public function addColumn()
    {
        if ($this->name || $this->timestamps) {
            $this->columns[] = [
                'column' => $this->name,
                'length' => $this->length,
                'dataType' => $this->dataType,
                'nullable' => $this->nullable,
                'unsigned' => $this->unsigned,
                'pk' => $this->pk,
                'timestamps' => $this->timestamps,
                'autoIncrement' => $this->autoIncrement,
                'default' => $this->default,
                'comment' => $this->comment,
                'unique' => $this->unique
            ];
            $this->reset();
        }
        if($this->foreignKey) {
            $this->columns[] = [
                'foreignKey' => $this->foreignKey,
                'references' => $this->references,
                'on' => $this->on,
                'onUpdate' => $this->onUpdate,
                'onDelete' => $this->onDelete,
            ];
            $this->reset();
        }
    }

    public function foreign(string $foreignKey)
    {
        $this->addColumn();
        $this->foreignKey = $foreignKey;
        return $this;
    }

    public function references($references)
    {
        $this->references = $references;
        return $this;
    }

    public function on($table)
    {
        $this->on = $table;
        return $this;
    }

    public function onUpdate($onUpdate)
    {
        $this->onUpdate = $onUpdate;
        return $this;
    }

    public function onDelete($onDelete)
    {
        $this->onDelete = $onDelete;
        return $this;
    }

    public function boolean($column)
    {
        $this->addColumn();
        $this->name = $column;
        $this->dataType = 'BOOLEAN';
        return $this;
    }

    public function bit($column)
    {
        $this->addColumn();
        $this->name = $column;
        $this->dataType = 'BIT';
        return $this;
    }

    public function bigInteger($column, $length = 20)
    {
        $this->addColumn();
        $this->name = $column;
        $this->length = $length;
        $this->dataType = 'BIGINT';
        return $this;
    }
    
    public function smallInteger($column, $length = 20)
    {
        $this->addColumn();
        $this->name = $column;
        $this->length = $length;
        $this->dataType = 'SMALLINT';
        return $this;
    }

    public function mediumInteger($column, $length = 20)
    {
        $this->addColumn();
        $this->name = $column;
        $this->length = $length;
        $this->dataType = 'MEDIUMINT';
        return $this;
    }

    public function decimal($column, $length = null)
    {
        $this->addColumn();
        $this->name = $column;
        $this->length = $length;
        $this->dataType = 'DECIMAL';
        return $this;
    }

    public function date($column)
    {
        $this->addColumn();
        $this->name = $column;
        $this->dataType = 'DATE';
        return $this;
    }

    public function dateTime($column)
    {
        $this->addColumn();
        $this->name = $column;
        $this->dataType = 'DATETIME';
        return $this;
    }

    public function timestamp($column)
    {
        $this->addColumn();
        $this->name = $column;
        $this->dataType = 'TIMESTAMP';
        return $this;
    }

    public function time($column)
    {
        $this->addColumn();
        $this->name = $column;
        $this->dataType = 'TIME';
        return $this;
    }

    public function year($column)
    {
        $this->addColumn();
        $this->name = $column;
        $this->dataType = 'YEAR';
        return $this;
    }

    public function integer($column, $length = 11)
    {
        $this->addColumn();
        $this->name = $column;
        $this->length = $length;
        $this->dataType = 'INT';
        return $this;
    }

    public function tinyInteger($column, $length = 2)
    {
        $this->addColumn();
        $this->name = $column;
        $this->length = $length;
        $this->dataType = 'TINYINT';
        return $this;
    }

    public function timestamps()
    {
        $this->addColumn();
        $this->timestamps = true;
        return $this;
    }

    public function bigIncrements($column = 'id', $length = 20)
    {
        $this->addColumn();
        $this->name = $column;
        $this->length = $length;
        $this->dataType = 'BIGINT';
        $this->pk = true;
        $this->autoIncrement = true;
        return $this;
    }

    public function increments($column = 'id', $length = 11)
    {
        $this->addColumn();
        $this->name = $column;
        $this->length = $length;
        $this->dataType = 'INT';
        $this->pk = true;
        $this->autoIncrement = true;
        return $this;
    }

    public function string($column, $length = 255)
    {
        $this->addColumn();
        $this->name = $column;
        $this->length = $length;
        $this->dataType = 'VARCHAR';
        return $this;
    }

    public function text($column, $length = 1000)
    {
        $this->addColumn();
        $this->name = $column;
        $this->length = $length;
        $this->dataType = 'TEXT';
        return $this;
    }

    public function longText($column, $length = 10000)
    {
        $this->addColumn();
        $this->name = $column;
        $this->length = $length;
        $this->dataType = 'LONGTEXT';
        return $this;
    }

    public function unsigned()
    {
        $this->unsigned = true;
        return $this;
    }

    public function default(string $value)
    {
        $this->default = $value;
        return $this;
    }

    public function comment(string $value)
    {
        $this->comment = $value;
        return $this;
    }

    public function nullable()
    {
        $this->nullable = true;
        return $this;
    }

    public function unique()
    {
        $this->unique = true;
        return $this;
    }

    public function done()
    {
        $this->addColumn();
        return $this;
    }

    public function columns()
    {
        $this->addColumn();
        return $this->columns;
    }

    public function __destruct()
    {
        $this->addColumn();
    }
}
