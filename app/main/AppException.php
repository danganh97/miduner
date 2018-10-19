<?php

namespace App\Main;

use \Exception;

class AppException extends Exception
{
    public function __construct($message, $code = null)
    {
        set_exception_handler([$this, 'catch_handle']);
        parent::__construct($message, $code);
    }

    public function catch_handle($exception)
    {
        $layoutsException = file_get_contents(Registry::getInstance()->config['APP_URL'] . '/resources/views/Exception.php');
        $title = $exception->getMessage();
        ob_start();
        echo '<hr>';
        echo "<h1 style='color:#b0413e;font-weight:bold'>{$exception->getMessage()}</h1>";
        echo "<h2>Error from file {$exception->getFile()} <br> in line {$exception->getLine()}</h2>";
        echo "<hr>";
        foreach ($exception->getTrace() as $trace) {
            $file = isset($trace['file']) ? $trace['file'] : '';
            $line = isset($trace['line']) ? $trace['line'] : '';
            $class = isset($trace['class']) ? $trace['class'] : '';
            $function = isset($trace['function']) ? $trace['function'] : '';

            if ($file !== '') {
                echo "<h5>File <strong>{$file}</strong> got error from class {$class} in function {$function}() (line {$line})</h5>";
                echo "<hr>";
            }
        }
        $content = ob_get_clean();
        $layoutsException = preg_replace('/\[exception\]/', $content, $layoutsException);
        $layoutsException = preg_replace('/\[title\]/', $title, $layoutsException);
        eval(' ?>' . $layoutsException);
    }
}
