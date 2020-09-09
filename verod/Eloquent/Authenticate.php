<?php

namespace Midun\Eloquent;

use Hash;
use Firebase\JWT\JWT;
use Midun\Auth\AuthenticationException;

abstract class Authenticate extends Model
{
    /**
     * Set password before saving
     * 
     * @param string $password
     * 
     * @return string
     */
    public function setPasswordAttribute($password)
    {
        return Hash::make($password);
    }

    /**
     * Create token for this user bound
     * 
     * @return string
     * 
     * @throws AuthenticationException
     */
    public function createToken(array $customClaims = [])
    {
        $key = config('jwt.secret');

        $hash = config('jwt.hash');

        if (empty($key)) {
            throw new AuthenticationException("Please install the JWT authentication");
        }

        if (empty($hash)) {
            throw new AuthenticationException("Please set hash type in config/jwt.php");
        }

        $modelId = $this->primaryKey();

        if (is_null($this->{$modelId})) {
            throw new AuthenticationException("Cannot generate tokens for the class that are not yet bound");
        }

        $jwt = app()->make(JWT::class);

        $minutes = isset($customClaims['exp']) ? $customClaims['exp'] : config('jwt.exp');

        $exp = strtotime('+ ' . $minutes . ' minutes');

        $payload = [
            'object' => $this,
            'exp' => $exp
        ];

        return [
            'token' => $jwt->encode($payload, $this->trueFormatKey($key), $hash),
            'exp' => $exp,
            'type' => 'Bearer'
        ];
    }

    /**
     * Make true format for jwt key
     * 
     * @param string $key
     * 
     * @return string
     */
    public function trueFormatKey(string $key)
    {
        return base64_decode(strtr($key, '-_', '+/'));
    }
}
