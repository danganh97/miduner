<?php

namespace Main\Http\Exceptions;

use Exception;

class AppException extends Exception
{
    private $exception;
    protected $code;

    public function __construct($message, $code = null)
    {
        if (PHP_SAPI === 'cli') {
            die($message);
        }
        set_exception_handler([$this, 'render']);
        $this->root = config('app.base');
        $this->code = $code;
        parent::__construct($message, $code);
        $this->report();
    }

    public function render($exception)
    {
        $this->exception = $exception;
        $header = getallheaders();
        if (isset($header['Accept']) && $header['Accept'] == 'application/json' || isset($header['Content-Type']) && $header['Content-Type'] == 'application/json') {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ], $this->code);
        }
        header('Content-Type: text/html');
        $layoutsException = file_get_contents($this->root . '/resources/views/Exception.php');
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
                echo "<h5>File <strong>{$file}</strong> got error from class {$class} in function {$function}() <strong>line {$line}</strong></h5>";
                echo "<hr>";
            }
        }
        $content = ob_get_clean();
        $layoutsException = preg_replace('/\[exception\]/', $content, $layoutsException);
        $layoutsException = preg_replace('/\[title\]/', $title, $layoutsException);
        eval(' ?>' . $layoutsException);
    }

    public function report()
    {
        // echo 'Reported !';
    }
}
