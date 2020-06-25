<?php

namespace Main\Traits\Eloquent;

use PDO;
use PDOException;
use Main\Database\Connection;
use Main\Http\Exceptions\AppException;

trait ExecuteQuery
{
    /**
     * Execute the query as a "select" statement.
     *
     * @param  array  $this
     * @return \SupportCollection
     */
    public function get()
    {
        $sql = $this->pase();
        return $this->request($sql);
    }

    /**
     * View query builder to sql statement.
     *
     * @return \SupportSqlCollection
     */
    public function toSql()
    {
        echo $this->pase();die();
    }

    /**
     * Convert variables to sql
     *
     * @return \SupportSqlCollection
     */
    public function pase()
    {
        if (!isset($this->table) || empty($this->table)) {
            return false;
        }
        $sql = $this->compile->compileSelect($this->distinct);
        $sql .= $this->compile->compileColumns($this->columns);
        $sql .= $this->compile->compileFrom($this->table);
        if (isset($this->joins) && is_array($this->joins)) {
            $sql .= $this->compile->compileJoins($this->joins);
        }
        if (isset($this->wheres) && is_array($this->wheres)) {
            $sql .= $this->compile->compileWheres($this->wheres);
        }
        if (isset($this->wherein)) {
            $sql .= $this->compile->compileWhereIn($this->wherein);
        }
        if (isset($this->groups) && is_array($this->groups)) {
            $sql .= $this->compile->compileGroups($this->groups);
        }
        if (isset($this->havings) && is_array($this->havings)) {
            $sql .= $this->compile->compileHavings($this->havings);
        }
        if (isset($this->orders) && is_array($this->orders)) {
            $sql .= $this->compile->compileOrders($this->orders);
        }
        if (isset($this->limit)) {
            $sql .= $this->compile->compileLimit($this->limit);
        }
        if (isset($this->offset)) {
            $sql .= $this->compile->compileOffset($this->offset);
        }
        return $sql;
    }

    /**
     * Create new record
     *
     * @param array data
     *
     * @return \SupportSqlCollection
     */
    public function insert(array $data)
    {
        $sql = $this->compile->compileInsert($this->table, $data);
        return $this->request($sql);
    }

    /**
     * Create new record
     *
     * @param array data
     *
     * @return \SupportSqlCollection
     */
    public function create(array $data)
    {
        if (!empty($this->calledFromModel)) {
            $object = (new $this->calledFromModel);
            $fillable = $object->fillable();
            $hidden = $object->hidden();
            $sql = $this->compile->compileCreate($this->table, $fillable, $hidden, $data);
            return $this->request($sql);
        } else {
            throw new Exception("Method 'create' doesn't exists");
        }
    }

    /**
     * Find 1 record usually use column id
     *
     * @param string value
     * @param string column
     * @return \SupportSqlCollection
     */
    public function find($value, $column = 'id')
    {
        $this->find = true;
        $this->limit = 1;
        $this->where($column, '=', $value);
        $sql = $this->compile->compileSelect($this->distinct);
        $sql .= $this->compile->compileColumns($this->columns);
        $sql .= $this->compile->compileFrom($this->table);
        $sql .= $this->compile->compileWheres($this->wheres);
        return $this->request($sql);
    }

    /**
     * Find 1 record usually use column id
     *
     * @param string value
     * @param string column
     * @return \SupportSqlCollection
     */
    public function findOrFail($value, $column = 'id')
    {
        $this->find = true;
        $this->limit = 1;
        $this->isThrow = true;
        $this->where($this->calledFromModel ? $this->getCalledModelInstance()->primaryKey() : $column, '=', $value);
        $sql = $this->compile->compileSelect($this->distinct);
        $sql .= $this->compile->compileColumns($this->columns);
        $sql .= $this->compile->compileFrom($this->table);
        $sql .= $this->compile->compileWheres($this->wheres);
        return $this->request($sql);
    }

    /**
     * First 1 record usually use column id
     *
     * @param string value
     * @param string column
     * @return \SupportSqlCollection
     */
    public function first()
    {
        $this->first = true;
        $this->limit = 1;
        $sql = $this->pase();
        return $this->request($sql);
    }

    /**
     * First 1 record usually use column id
     *
     * @param string value
     * @param string column
     * @return \SupportSqlCollection
     */
    public function firstOrFail()
    {
        $this->first = true;
        $this->isThrow = true;
        $this->limit = 1;
        $sql = $this->pase();
        return $this->request($sql);
    }
    /**
     * Quick login with array params
     *
     * @param array data
     * @return \SupportSqlCollection
     */
    public function login(array $data)
    {
        $this->find = true;
        $sql = $this->compile->compileLogin($this->table, $data);
        return $this->request($sql);
    }

    /**
     * Destroy a record from condition
     *
     * @return \SupportSqlCollection
     */
    public function delete()
    {
        $sql = $this->compile->compileDelete($this->table);
        $sql .= $this->compile->compileWheres($this->wheres);
        return $this->request($sql);
    }

    /**
     * Update records from condition
     *
     * @param array data
     * @return \SupportSqlCollection
     */
    public function update(array $data)
    {
        $sql = $this->compile->compileUpdate($this->table, $data);
        $sql .= $this->compile->compileWheres($this->wheres);
        return $this->request($sql);
    }

    /**
     * Execute sql
     *
     * @param string sql
     * @return \SupportSqlCollection
     */
    public function request($sql)
    {
        try {
            $connection = (new Connection)->getConnection();
            $object = $connection->prepare($sql);
            $object->execute();
            $type = explode(" ", $sql);
            switch ($type[0]) {
                case 'SELECT':
                    return $this->inCaseSelect($object);
                case 'INSERT':
                    return $this->inCaseInsert($connection);
                case 'UPDATE':
                    return $this->find($this->wheres[0][0], $this->wheres[0][2]);
                default:
                    return $object;
            }
        } catch (PDOException $e) {
            throw new AppException($e->getMessage());
        }
    }

    private function getOneItemHasModel($connection)
    {
        // $primaryKey = (new $this->calledFromModel)->primaryKey();
        $primaryKey = self::getCalledModelInstance()->primaryKey();
        return $this->find($connection->lastInsertId(), $primaryKey);
    }

    private static function calledModelInstance()
    {
        return self::getCalledModelInstance();
    }

    /**
     * Exec sql get column id in connection
     *
     * @param Object $connection
     *
     */
    private function sqlExecGetColumnIdInConnection($connection)
    {
        $lastInsertId = $connection->lastInsertId();
        $getConfigFromConnection = (new Connection);
        $connection = $getConfigFromConnection->getConnection();
        $databaseName = $getConfigFromConnection->config['database'];
        $newObject = $connection->prepare($this->createSqlStatementGetColumnName($databaseName));
        $newObject->execute();
        return $this->find($lastInsertId, $newObject->fetch()->COLUMN_NAME);
    }

    /**
     * Create sql statement get column name
     *
     * @param String $databaseName
     *
     */
    private function createSqlStatementGetColumnName($databaseName)
    {
        return "
            SELECT
                COLUMN_NAME
            FROM
                INFORMATION_SCHEMA.COLUMNS
            WHERE
                TABLE_SCHEMA = '{$databaseName}' AND
                TABLE_NAME = '{$this->table}' AND EXTRA = 'auto_increment'
        ";
    }

    /**
     * Handle in case insert SQL
     *
     * @param Object $connection
     *
     */
    private function inCaseInsert($connection)
    {
        if (!empty($this->calledFromModel)) {
            return $this->getOneItemHasModel($connection);
        }
        return $this->sqlExecGetColumnIdInConnection($connection);
    }

    /**
     * Handle in case select SQL
     *
     * @param Object $object
     *
     */
    private function inCaseSelect($object)
    {
        if ($this->find === true || $this->first === true) {
            if (!empty($this->calledFromModel)) {
                return $this->fetchOnlyOneItemHasModel($object);
            }
            return $object->fetch();
        }
        if (!empty($this->calledFromModel)) {
            return $this->fetchListItemsHasModel($object);
        }
        return $this->fetchOneItemWithoutModel($object);
    }

    /**
     * Fetch one item without model
     *
     * @param Object $object
     *
     */
    private function fetchOneItemWithoutModel($object)
    {
        return $object->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Fetch list items has model
     *
     * @param Object $object
     *
     */
    private function fetchListItemsHasModel($object)
    {
        $resources = $object->fetchAll(PDO::FETCH_CLASS, $this->calledFromModel);
        foreach ($resources as $resource) {
            if (!empty($this->with)) {
                foreach ($this->with as $w) {
                    if (method_exists($resource, $w)) {
                        $resource->$w = $resource->$w();
                    } else {
                        throw new AppException("Method '{$w}' not found in class {$this->calledFromModel}");
                    }
                }
            }
        }
        return $resources;
    }

    /**
     * Fetch one item has model
     *
     * @param Object $object
     * @throws ModelException
     *
     */
    private function fetchOnlyOneItemHasModel($object)
    {
        $object = $object->fetchAll(PDO::FETCH_CLASS, $this->calledFromModel);
        if (is_array($object) && count($object) > 0) {
            if (!empty($this->with)) {
                $object = $object[0];
                foreach ($this->with as $w) {
                    if (method_exists($object, $w)) {
                        $object->$w = $object->$w();
                    } else {
                        throw new AppException("Method '{$w}' not found in class {$this->calledFromModel}");
                    }
                }
            }
            return $object;
        }
        if ($this->isThrow === true) {
            throw new AppException("Resource not found", 404);
        }
        return null;
    }
}
