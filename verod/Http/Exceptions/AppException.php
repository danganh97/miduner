<?php

namespace Midun\Http\Exceptions;

use Exception;
use Midun\Application;

class AppException extends Exception
{
    public function __construct($message, $code = 400)
    {
        $this->writeLog($message);

        parent::__construct($message, $code);

        if (PHP_SAPI === 'cli') {
            die($message);
        }

        set_exception_handler([$this, 'render']);

        $this->report();
    }

    /**
     * Render exception 
     * 
     * @param \Exception $exception
     * 
     * @return mixed
     */
    public function render($exception)
    {
        if (request()->isAjax()) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
            ], $this->code);
        }

        return app('view')->render('exception', compact('exception'));
    }

    /**
     * Write exception message to log
     * 
     * @param string $message
     * 
     * @return void
     */
    private function writeLog(string $message)
    {
        if (app()->make(Application::class)->isLoaded()) {

            app()->make('log')->error(
                (new \ReflectionClass(
                    static::class
                ))->getShortName() . " throws $message from " . $this->getFile() . " line " . $this->getLine()
            );
        }
    }

    /**
     * Report exception
     * 
     * @return void
     */
    protected function report()
    {
        // echo 'Reported !';
    }
}
