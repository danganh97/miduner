<?php

namespace Midun\Traits\Eloquent;

use PDO;
use PDOException;
use PDOStatement;
use Midun\Eloquent\ModelBindingObject;
use Midun\Database\Connections\Mysql\Connection;
use Midun\Database\QueryBuilder\QueryException;

trait ExecuteQuery
{
    /**
     * Execute sql
     *
     * @param string sql
     * @return \SupportSqlCollection
     */
    public function request($sql)
    {
        try {
            $connection = app()->make('connection')->getConnection();
            switch (true) {
                case $this->isInsertQuery($sql):
                    $object = $connection->query($sql);
                    break;
                case $this->isSelectQuery($sql):
                case $this->isUpdateQuery($sql):
                case $this->isDeleteQuery($sql):
                    $object = $connection->prepare($sql);
                    $this->bindingParams($object);
                    $object->execute();
                    break;
            }
            $this->rowCount = $object->rowCount();
            return $this->buildResponse($sql, $object, $connection);
        } catch (PDOException $e) {
            throw new QueryException($e->getMessage());
        }
    }

    /**
     * Check is insert query
     * 
     * @param string $query
     * 
     * @return boolean
     */
    public function isInsertQuery(string $query)
    {
        $parse = explode(' ', $query);
        $queryType = array_shift($parse);

        return 'INSERT' === strtoupper(trim($queryType));
    }

    /**
     * Check is update query
     * 
     * @param string $query
     * 
     * @return boolean
     */
    public function isUpdateQuery(string $query)
    {
        $parse = explode(' ', $query);
        $queryType = array_shift($parse);

        return 'UPDATE' === strtoupper(trim($queryType));
    }
    /**
     * Check is select query
     * 
     * @param string $query
     * 
     * @return boolean
     */
    public function isSelectQuery(string $query)
    {
        $parse = explode(' ', $query);
        $queryType = array_shift($parse);

        return 'SELECT' === strtoupper(trim($queryType));
    }
    /**
     * Check is delete query
     * 
     * @param string $query
     * 
     * @return boolean
     */
    public function isDeleteQuery(string $query)
    {
        $parse = explode(' ', $query);
        $queryType = array_shift($parse);

        return 'DELETE' === strtoupper(trim($queryType));
    }

    /**
     * Building response
     * @param string $sql
     * @param PDOStatement $object
     * @param Connection $connection
     * 
     * @return void
     */
    private function buildResponse($sql, $object, $connection)
    {
        $type = explode(" ", $sql);
        switch (array_shift($type)) {
            case 'SELECT':
                return $this->inCaseSelect($object);
            case 'INSERT':
                return $this->inCaseInsert($connection);
            case 'UPDATE':
                return true;
            case 'DELETE':
                return true;
            default:
                return $object;
        }
    }
    /**
     * Binding parameters to sql statements
     * 
     * @param PDOStatement $object
     * 
     * @return void
     */
    private function bindingParams(PDOStatement $object)
    {
        if (!is_null($this->parameters)) {
            foreach ($this->parameters as $key => $param) {
                $object->bindParam($key + 1, $param);
            }
        }
    }

    /**
     * Get one row has model instance
     * 
     * @param Connection $connection
     */
    private function getOneItemHasModel($connection)
    {
        $primaryKey = $this->getCalledModelInstance()->primaryKey();
        return $this->find($connection->lastInsertId(), $primaryKey);
    }

    private function getCalledModelInstance()
    {
        return new $this->calledFromModel;
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
        $getConfigFromConnection = app()->make('connection');
        $connection = $getConfigFromConnection->getConnection();
        $databaseName = $getConfigFromConnection->getConfig()[3];
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
                return $this->execBindingModelObject($object);
            }
            return $object->fetch();
        }
        if (!empty($this->calledFromModel)) {
            return $this->execBindingModelObject($object);
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
     * Execute binding model object
     *
     * @param PDOStatement $pdoStatementObject
     *
     * @return ModelBindingObject
     *
     */
    private function execBindingModelObject(PDOStatement $pdoStatementObject)
    {
        $resources = $pdoStatementObject->fetchAll(PDO::FETCH_CLASS, $this->calledFromModel);
        return (new ModelBindingObject($resources))->receive(
            $this->find || $this->first,
            !$this->find && !$this->first,
            $this->calledFromModel ?: null,
            [
                'with' => $this->with,
            ],
            $this->isThrow
        );
    }
}
