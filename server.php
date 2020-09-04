<?php

/**
 * Miduner - A PHP Framework For Pure PHP
 *
 * @package  Miduner
 * @author   Dang Anh <danganh.dev@gmail.com>
 */

if (preg_match('/\.(?:png|jpg|jpeg|gif|css|js|woff2)$/', $_SERVER["REQUEST_URI"])) {
    return false;    // serve the requested resource as-is.
}
require_once __DIR__ . '/public/index.php';
