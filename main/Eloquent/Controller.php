<?php

namespace Main\Eloquent;

use App\Http\Exceptions\Exception;
use Main\Http\Exceptions\AppException;
use Main\Traits\Response\JsonResponse;

class Controller
{
    use JsonResponse;

    private $master;
    private $vars = [];

    public function __construct()
    {
        $this->app_base = config('app.base');
    }

    public function setVars($key, $value)
    {
        $this->vars[$key] = $value;
    }

    public function setMaster($view)
    {
        $this->master = $view;
    }

    public function render($view, $data = null)
    {
        $root = $this->app_base;
        $viewPath = $root . '/resources/views/' . $view . '.php';
        if (!file_exists($viewPath)) {
            throw new Exception('Not found view ' . $viewPath);
        }
        if (is_array($data)) {
            extract($data, EXTR_PREFIX_SAME, "data");
        }
        $content = $this->getViewContent($view, $data);
        if($this->master) {
            $layoutPath = $root . '/resources/views/' . str_replace('.', '/', $this->master) . '.php';
            if (!file_exists($layoutPath)) {
                throw new AppException("Layout $layoutPath not found");
            }
            $layouts = file_get_contents($layoutPath);
            foreach ($this->vars as $key => $value) {
                $layouts = preg_replace('/\[' . $key . '\]/', $value, $layouts);
            }
            $layouts = preg_replace('/\[content\]/', $content, $layouts);
            eval(' ?>' . $layouts);
        } else {
            eval(' ?>' . $content);
        }
        return true;
        
    }

    public function getViewContent($view, $data = null)
    {
        $root = $this->app_base;
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
        $root = $this->app_base;
        $path = $root . '/resources/views/' . $view . '.php';
        if (is_array($data)) {
            extract($data, EXTR_PREFIX_SAME, "data");
        }
        if (file_exists($path)) {
            require $path;
        }
    }
}
