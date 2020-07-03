<?php

namespace Main\Hashing;

use Main\Contracts\Hashing\Hasher;
use Main\Http\Exceptions\AppException;

class BcryptHasher implements Hasher
{
    /**
     * Hash the given value.
     *
     * @param  string  $value
     * @param  array   $options
     * @return string
     *
     * @throws \Main\Http\Exceptions\AppException
     */
    public function make($value, array $options = [])
    {
        $hash = password_hash($value, PASSWORD_BCRYPT, $options);
        if ($hash === false) {
            throw new AppException('Bcrypt hashing not supported.');
        }

        return $hash;
    }

    /**
     * Check the given plain value against a hash.
     *
     * @param  string  $value
     * @param  string  $hashedValue
     * @param  array   $options
     * @return bool
     */
    public function check($value, $hashedValue, array $options = [])
    {
        if (strlen($hashedValue) === 0) {
            return false;
        }

        return password_verify($value, $hashedValue);
    }
}
