<?php

namespace App\Main;

use App\Main\Registry;

class Controller
{
    private $layout = null;
    private $vars = [];

    public function __construct()
    {
        $this->app_url = config('app.appurl');
        $this->layout = config('app.layout');
    }

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function setVars($key, $value)
    {
        $this->vars[$key] = $value;
    }

    public function render($view, $data = null)
    {
        $root =  $this->app_url;
        $layoutPath = $root . '/resources/views/layouts/' . $this->layout . '.php';
        $viewPath = $root . '/resources/views/' . $view . '.php';
        if (!file_exists($viewPath)) {
            throw new \App\Main\AppException('Not found view ' . $viewPath);
        }
        if (is_array($data)) {
            extract($data, EXTR_PREFIX_SAME, "data");
        }
        $content = $this->getViewContent($view, $data);
        if ($this->layout !== null) {
            if (file_exists($layoutPath)) {
                $layouts = file_get_contents($layoutPath);
                foreach ($this->vars as $key => $value) {
                    $layouts = preg_replace('/\[' . $key . '\]/', $value, $layouts);
                }
                $layouts = preg_replace('/\[content\]/', $content, $layouts);
                eval(' ?>' . $layouts);
            }
        }
    }

    public function singleRender($view, $data = null)
    {
        $root =  $this->app_url;
        $path = $root . '/resources/views/' . $view . '.php';
        if (is_array($data)) {
            extract($data, EXTR_PREFIX_SAME, "data");
        }
        if ($path !== null) {
            if (file_exists($path)) {
                require $path;
            }
        }
    }

    public function getViewContent($view, $data = null)
    {
        $root =  $this->app_url;
        $path = $root . '/resources/views/' . $view . '.php';
        if (is_array($data)) {
            extract($data, EXTR_PREFIX_SAME, "data");
        }
        if (file_exists($path)) {
            ob_start();
            require $path;
            return ob_get_clean();
        }
    }

    public function partialRender($view, $data = null)
    {
        $root =  $this->app_url;
        $path = $root . '/resources/views/' . $view . '.php';
        if (is_array($data)) {
            extract($data, EXTR_PREFIX_SAME, "data");
        }
        if (file_exists($path)) {
            require $path;
        }
    }

    public function toCollection($arg)
    {
        return json_decode(json_encode($arg));
    }
}
