<?php

namespace Midun\Traits\Eloquent;

use Midun\Http\Exceptions\AppException;

trait CommitQueryMethod
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
        echo $this->pase();
        exit(0);
    }

    /**
     * Get full sql statement
     */
    public function getFullSql()
    {
        return $this->pase();
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
            $object = new $this->calledFromModel;
            $fillable = $object->fillable();
            $hidden = $object->hidden();
            $columns = array_merge($fillable, $hidden);
            $sql = $this->compile->compileCreate($object, $columns, $data);
            return $this->request($sql);
        }
        throw new AppException("Method 'create' doesn't exists");
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

        if (!is_null($this->existsModelInstance)) {
            $model = $this->existsModelInstance;
            $primaryKey = $model->primaryKey();
            $valueKey = $model->$primaryKey;
            $this->where($primaryKey, $valueKey);
        }
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

        if (!is_null($this->existsModelInstance)) {
            $model = $this->existsModelInstance;
            $primaryKey = $model->primaryKey();
            $valueKey = $model->$primaryKey;
            $this->where($primaryKey, $valueKey);
            $sql .= $this->compile->compileWheres($this->wheres);
        }

        return $this->request($sql);
    }

    /**
     * Begin transaction
     * 
     * @return bool
     */
    public function beginTransaction()
    {
        return app()->make('connection')->getConnection()->{__FUNCTION__}();
    }

    /**
     * Commit transaction
     * 
     * @return bool
     */
    public function commit()
    {
        return app()->make('connection')->getConnection()->{__FUNCTION__}();
    }

    /**
     * Rollback transaction
     * 
     * @return bool
     */
    public function rollBack()
    {
        return app()->make('connection')->getConnection()->{__FUNCTION__}();
    }
}
