<?php

namespace App\Main\Eloquent;

use App\Http\Exceptions\Exception;
use App\Main\Database\QueryBuilder\DB;
use App\Main\Traits\Eloquent\GetAttribute;
use App\Main\Traits\Eloquent\With;
use App\Main\Traits\Instance;

abstract class Model
{
    use With, GetAttribute, Instance;

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

    const LIST_OF_ORM_METHODS = [
        'with',
    ];

    public function __construct()
    {
        app()->callModel = get_called_class();
        $this->callServiceAppends();
        $this->callServiceCasts();
        $this->callServiceGetAttributes();
        $this->callServiceHidden();
        $this->callServiceWithes();
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function __set($property, $value)
    {
        $this->$property = $value;
    }

    public static function __callStatic($method, $args)
    {
        if (in_array($method, self::LIST_OF_ORM_METHODS)) {
            $property = "{$method}s";
            list($function) = $args;
            static::getInstance()->$property = $function;
        }
        return static::getInstance()->execCallStatic($method, $args);
    }

    public function __call($method, $args)
    {
        return static::getInstance()->execCallStatic($method, $args);
    }

    public function execCallStatic($method, $args)
    {
        return DB::staticEloquentBuilder($this->table, $method, $args);
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

    public function callServiceWithes()
    {
        if ($this->withes) {
            foreach ($this->withes as $with) {
                $function = "{$with}Exec";
                $this->$with = $this->$function();
            }
        }
    }
}
