<?php

if (!function_exists('view')) {
    /**
     * Render view file
     * 
     * @param string $view
     * @param array $data
     * 
     * @return void
     */
    function view(string $view = '', array $data = [])
    {
        return $view ? app()->make('view')->render($view, $data) : app()->make('view');
    }
}

if (!function_exists('master')) {
    /**
     * Set master layout
     * 
     * @param string $master
     * 
     * @return void
     */
    function master(string $master)
    {
        app()->make('view')->setMaster($master);
    }
}

if (!function_exists('setVar')) {
    /**
     * Set variable for view
     * 
     * @param string $key
     * @param mixed $value
     * 
     * @return void
     */
    function setVar(string $key, $value)
    {
        app()->make('view')->setVar($key, $value);
    }
}

if (!function_exists('need')) {
    /**
     * Needed section
     * 
     * @param string $section
     * 
     * @return void
     */
    function need(string $section, string $instead = '')
    {
        echo app()->make('view')->getNeedSection($section, $instead);
    }
}

if (!function_exists('section')) {
    /**
     * Start section
     * 
     * @param string $section
     * @param mixed $data
     * 
     * @return void
     */
    function section(string $section, $data = null)
    {
        if (!is_null($data)) {
            app()->make('view')->setSectionWithData($section, $data);
        } else {
            app()->make('view')->setCurrentSection($section);
        }
    }
}

if (!function_exists('endsection')) {
    /**
     * End section
     * 
     * @return void
     */
    function endsection()
    {
        app()->make('view')->setDataForSection(ob_get_clean());
    }
}

if (!function_exists('included')) {
    /**
     * Include partial view
     * 
     * @param string $path
     * 
     * @return void
     */
    function included(string $path)
    {
        $path = str_replace('.', DIRECTORY_SEPARATOR, $path);

        app()->make('view')->makeCache($path);

        $path = cache_path("resources/views/{$path}.php");

        if (file_exists($path)) {
            include $path;
        } else {
            throw new \Midun\Http\Exceptions\AppException("Cache $path not found.");
        }
    }
}
