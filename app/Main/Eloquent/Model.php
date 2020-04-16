<?php

namespace App\Main\Eloquent;

use App\Http\Exceptions\Exception;
use App\Main\QueryBuilder as DB;
use App\Main\Traits\Eloquent\GetAttribute;
use App\Main\Traits\Eloquent\With;

abstract class Model
{
    use With, GetAttribute;

    protected $appends = [];
    protected $casts = [];
    protected $fillable = [];
    protected $hidden = [];
    protected $table;
    protected $primaryKey;
    protected $username;
    protected $password;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function __construct()
    {
        app()->callModel = get_called_class();
        $this->callServiceAppends();
        $this->callServiceCasts();
        $this->callServiceGetAttributes();
        $this->callServiceHidden();
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }

    public function getTable()
    {
        return $this->table;
    }

    public function findStatic($param)
    {
        return DB::table($this->table)->find($param, $this->primaryKey);
    }

    public function firstStatic()
    {
        return DB::table($this->table)->first();
    }

    public function createStatic($data)
    {
        return DB::table($this->table)->create($data);
    }

    public function getStatic($column)
    {
        return DB::table($this->table)->select($column)->get();
    }

    public function loginStatic($data)
    {
        return $this->table == 'users' ? DB::table($this->table)->login([$this->username => $data[$this->username], $this->password => $data[$this->password]]) : false;
    }

    public static function create($data)
    {
        return (new static)->createStatic($data);
    }

    public static function find($param)
    {
        return (new static)->findStatic($param);
    }

    public static function first()
    {
        return (new static)->firstStatic();
    }

    public static function get($column = ['*'])
    {
        return (new static)->getStatic($column);
    }

    public static function login($data)
    {
        return (new static)->loginStatic($data);
    }

    private function callServiceAppends()
    {
        foreach ($this->appends as $key => $value) {
            $values = explode('_', $value);
            $func = '';
            foreach ($values as $app) {
                $func .= ucfirst($app);
            }
            $this->$value = call_user_func([$this, "get{$func}Attribute"]);
        }
    }

    private function callServiceCasts()
    {
        foreach ($this->casts as $key => $value) {
            if (!in_array($key, $this->fillable)) {
                throw new Exception("The attribute $key not exists in fillable.");
            }
            switch ($value) {
                case 'int':
                    $this->{$key} = isset($this->{$key}) ? (int) $this->{$key} : null;
                    break;
                case 'array':
                    $this->{$key} = isset($this->{$key}) ? (array) $this->{$key} : null;
                    break;
                case 'object':
                    $this->{$key} = isset($this->{$key}) ? (object) $this->{$key} : null;
                    break;
                case 'float':
                    $this->{$key} = isset($this->{$key}) ? (float) $this->{$key} : null;
                    break;
                case 'double':
                    $this->{$key} = isset($this->{$key}) ? (float) $this->{$key} : null;
                    break;
                case 'string':
                    $this->{$key} = isset($this->{$key}) ? (string) $this->{$key} : null;
                    break;
                case 'boolean':
                    $this->{$key} = isset($this->{$key}) ? (bool) $this->{$key} : null;
                    break;
            }
        }
    }

    private function callServiceGetAttributes()
    {
        foreach (get_class_methods($this) as $key => $value) {
            if (strpos($value, 'get') !== false && strpos($value, 'Attribute') !== false) {
                $this->handleServiceGetAttribute($value);
            }
        }
    }

    private function handleServiceGetAttribute(string $value)
    {
        $pazeGet = str_replace('get', '', $value);
        $pazeAttribute = str_replace('Attribute', '', $pazeGet);
        foreach ($this->fillable as $fillable) {
            $values = explode('_', $fillable);
            $func = '';
            foreach ($values as $app) {
                $func .= ucfirst($app);
            }
            if ($func == $pazeAttribute) {
                $this->$fillable = call_user_func([$this, "get{$func}Attribute"]);
            }
        }
    }

    public function callServiceHidden()
    {
        foreach ($this->hidden as $hidden) {
            unset($this->$hidden);
        }
    }
}
