<?php

namespace Main\Contracts\Auth;

interface Authentication
{
    public function attempt($options = []);
}
