<?php

include 'view.php';

if (!function_exists('redirect')) {
    /**
     * Redirect to url
     * 
     * @param string $url
     * 
     * @return void
     */
    function redirect(string $url)
    {
        header('Location: ' . $url);
    }
}

if (!function_exists('response')) {
    /**
     * Make instance of response
     * 
     * @return \Midun\Supports\Response\DataResponse
     */
    function response()
    {
        return app()->make(__FUNCTION__);
    }
}

if (!function_exists('session')) {
    /**
     * Working on session
     * 
     * @return \Midun\Session\Session
     */
    function session()
    {
        return app()->make(__FUNCTION__);
    }
}

if (!function_exists('unsetsession')) {
    /**
     * Calling unset_session method in Session
     * 
     * @param string $key
     */
    function unsetsession(string $key)
    {
        app()->make('session')->unset_session($key);
    }
}

if (!function_exists('request')) {
    /**
     * Get instance of request
     * 
     * @return \Midun\Http\Request
     */
    function request()
    {
        return app()->make(__FUNCTION__);
    }
}

if (!function_exists('readDotENV')) {
    /**
     * Reading .env file
     */
    function readDotENV()
    {
        $path = base_path('.env');
        if (!file_exists($path)) {
            system("echo " . 'Missing .env file.');
            exit;
        }
        return parse_ini_file($path);
    }
}
if (!function_exists('env')) {
    /**
     * Get value from environments
     * 
     * @param string $variable
     * @param string $ndvalue
	 *
	 * @return string
     */
    function env(string $variable, string $ndvalue = null)
    {
        $path = cache_path('environments.php');
        if (!file_exists($path)) {
            die('Missing cache environment file');
        }
        $env = include $path;

        return isset($env[$variable]) ? $env[$variable] : $ndvalue;
    }
}

if (!function_exists('config')) {
    /**
     * Get config setting
     * 
     * @param string $variable
     * 
     * @return mixed
     */
    function config(string $variable)
    {
        return app()->make(__FUNCTION__)->getConfig($variable);
    }
}

if (!function_exists('trans')) {
    /**
     * Get translate value
     * 
     * @param string $variable
     * @param array $params
     * @param string $lang
     * 
     * @return string
     */
    function trans($variable, $params = [], $lang = 'en')
    {
        return app()->make('translator')->trans($variable, $params, $lang);
    }
}

if (!function_exists('__')) {
    /**
     * Get translate value without params
     */
    function __($variable, $lang = 'en')
    {
        return trans($variable, [], $lang);
    }
}

if (!function_exists('action')) {
    /**
     * Return action to controller method
     * 
     * @param mixed $action
     * @param array @params
     * 
     * @return void
     */
    function action(array $action, array $params = [])
    {
        return app()->make('route')->callableAction($action, $params);
    }
}

if (!function_exists('route')) {
    /**
     * Get uri of route from name
     * 
     * @param string $name
     * 
     * @return string
     */
    function route(string $name)
    {
        $routes =  app()->make(__FUNCTION__)->collect();
        $flag = false;
        $uri = '';
        foreach ($routes as $key => $route) {
            if (strtolower($name) === strtolower($route->getName())) {
                $flag = true;
                $uri = $route->getUri();
            }
        }
        if ($flag === true) {
            echo $uri;
        } else {
            throw new Exception("The route " . '"' . $name . '"' . " doesn't exists");
        }
    }
}

if (!function_exists('app')) {
    /**
     * Get instance of Container or make somethings
     * 
     * @param $entity
     * 
     * @return mixed
     */
    function app($entity = null)
    {
        if (is_null($entity)) {
            return \Midun\Container::getInstance();
        }
        return \Midun\Container::getInstance()->make($entity);
    }
}

if (!function_exists('is_json')) {
    /**
     * Checking argument is json format
     * 
     * @param mixed $argument
     * 
     * @return boolean
     */
    function is_json($argument)
    {
        return (json_decode(json_encode($argument)) != null) ? true : false;
    }
}

if (!function_exists('dd')) {
    /**
     * @param mixed $x
     *
     * @return die
     */
    function dd($x)
    {
        $x = is_array($x) ? $x : func_get_args();
        array_map(static function ($x) {
            var_dump($x);
        }, $x);
        die;
    }
}

if (!function_exists('assets')) {
    /**
     * Get path for resources
     * 
     * @param string $path
     * 
     * @return string
     * 
     * @throws \Exception
     */
    function assets($path)
    {
        if (php_sapi_name() == 'cli-server') {
            return "/public/$path";
        } else {
            return $path;
        }
        throw new Exception("");
    }
}

if (!function_exists('auth')) {
    /**
     * Get instance of auth
     * 
     * @return \Midun\Auth\Authenticatable
     */
    function auth()
    {
        return app()->make(__FUNCTION__);
    }
}

if (!function_exists('getallheaders')) {
    /**
     * Get all headers
     * 
     * @return array
     */
    function getallheaders()
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}

if (!function_exists('compileWatchingViews')) {
    /**
     * Compile watching view
     * 
     * @param string $view
     * 
     * @return void
     */
    function compileWatchingViews($view)
    {
        $folder = explode('/', $view);
        $file = array_pop($folder);
        $folder =  implode('/', $folder);
        writeCache($folder, $file);
    }
}

if (!function_exists('writeCache')) {
    /**
     * Write caching
     * 
     * @param string $folder
     * @param string $file
     * 
     * @return void
     */
    function writeCache(string $folder, string $file)
    {
        $cacheDir = 'storage/cache/';

        if (false == check_dir($cacheDir)) {
            make_dir($cacheDir);
        }
        $filePath = base_path("$folder/$file");
        $data = file_get_contents($filePath);
        $data = str_replace('{{', '<?php', $data);
        $data = str_replace('}}', '?>', $data);
        foreach (explode('/', $folder) as $f) {
            if ($f != '') {
                $cacheDir .= $f . DIRECTORY_SEPARATOR;
            }
            if (!check_dir($cacheDir)) {
                make_dir($cacheDir);
                $cacheDir .= '/';
            }
        }
        if (false == check_dir($cacheDir)) {
            make_dir($cacheDir);
        }
        $cacheFilePath = base_path("$cacheDir/$file");
        $cacheFile = fopen($cacheFilePath, "w") or die("Unable to open file!");
        fwrite($cacheFile, $data);
        fclose($cacheFile);
    }
}

if (!function_exists('objectToArray')) {
    /**
     * Convert object to array
     * 
     * @param array $inputs
     */
    function objectToArray($inputs)
    {
        $array = [];

        foreach ($inputs as $object) {
            $array[] = get_object_vars($object);
        }

        return $array;
    }
}

if (!function_exists('dispatch')) {
    /**
     * Dispatch a job
     * 
     * @param \Midun\Queues\Queue
     * 
     * @return void
     */
    function dispatch(\Midun\Queues\Queue $queue)
    {
        return app()->make(\Midun\Contracts\Bus\Dispatcher::class)->dispatch($queue);
    }
}

if (!function_exists('realTimeOutput')) {
    /**
     * @link https://www.hashbangcode.com/article/overwriting-command-line-output-php
     * 
     * @param array $output
     * 
     * @return void
     */
    function realTimeOutput(array $output)
    {
        static $oldLines = 0;
        $numNewLines = count($output) - 1;

        if ($oldLines == 0) {
            $oldLines = $numNewLines;
        }

        echo implode(PHP_EOL, $output);
        echo chr(27) . "[0G";
        echo chr(27) . "[" . $oldLines . "A";

        $numNewLines = $oldLines;
    }
}

if (!function_exists('logger')) {
    /**
     * Get instance of logger
     */
    function logger()
    {
        return app()->make('log');
    }
}

if (!function_exists('check_file')) {
    /**
     * Check file exists in project
     * 
     * @param string $file
     * 
     * @return boolean
     */
    function check_file(string $file)
    {
        return file_exists(base_path($file));
    }
}

if (!function_exists('check_dir')) {
    /**
     * Check exists directory
     * 
     * @param string $dir
     * 
     * @return boolean
     */
    function check_dir(string $dir)
    {
        return is_dir(base_path($dir));
    }
}

if (!function_exists('public_path')) {
    /**
     * Get public path
     * @param string $path
     * 
     * @return string
     */
    function public_path($path = '')
    {
        return app('path.public') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('cache_path')) {
    /**
     * Get cache path
     * @param string $path
     * 
     * @return string
     */
    function cache_path($path = '')
    {
        return app('path.cache') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('config_path')) {
    /**
     * Get config path
     * @param string $path
     * 
     * @return string
     */
    function config_path($path = '')
    {
        return app('path.config') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('database_path')) {
    /**
     * Get database path
     * @param string $path
     * 
     * @return string
     */
    function database_path($path = '')
    {
        return app('path.database') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('storage_path')) {
    /**
     * Return storage path
     * 
     * @param string $path
     * 
     * @return string
     */
    function storage_path($path = '')
    {
        return app('path.storage') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('route_path')) {
    /**
     * Return storage path
     * 
     * @param string $path
     * 
     * @return string
     */
    function route_path($path = '')
    {
        return app('path.route') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('base_path')) {
    /**
     * Get full path from base
     * 
     * @param string $path
     * 
     * @return string
     */
    function base_path(string $path = '')
    {
        return app()->basePath() . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('make_dir')) {
    /**
     * Make directory from base
     * 
     * @param string $dir
     * @param int $mode
     * @param bool $recursive
     * 
     * @return bool
     */
    function make_dir($dir, $mode = 0777, $recursive = false)
    {
        return mkdir(base_path($dir), $mode, $recursive);
    }
}

if (!function_exists('cacheExists')) {
    /**
     * Check exists caching
     * 
     * @param string $cacheFile
     * 
     * @return bool
     */
    function cacheExists(string $cacheFile)
    {
        return check_file('storage/cache/' . $cacheFile);
    }
}

if (!function_exists('generateRandomString')) {
    /**
     * Random string generator
     * 
     * @param int $length = 10
     * 
     * @return string
     */
    function generateRandomString($length = 10)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

if (!function_exists('stringToKeywords')) {
    /**
     * Parse a string to list keywords
     * 
     * @param string $str
     * @param int $min
     * minimum length of word
     * @param int $max
     * maximum length of word
     * 
     * @return array
     */
    function stringToKeywords(string $str, int $min = 1, int $max = null)
    {
        $array = explode(' ', $str);

        $init = $array;

        if ($min === 1) {
            $all = $array;
        } else {
            $all = [];
        }

        if (is_null($max)) {
            $max = count($array);
        }

        for ($i = $min; $i <= $max; $i++) {
            $words = [];

            $unshift = $i - 1;

            $array = $init;

            while (!empty($array) && !is_null($array[0])) {
                $cCollect = [];
                for ($j = 1; $j <= $i; $j++) {
                    $cCollect[] = array_shift($array);
                    if ($j == $i) {
                        $collectSize = count($cCollect);

                        for ($k = 1; $k <= $unshift; $k++) {
                            array_unshift($array, $cCollect[$collectSize - $k]);
                        }
                    }
                }

                $cCollect = array_filter($cCollect, function ($collect) {
                    return !empty($collect);
                });

                if (count($cCollect) < $i) {
                    continue;
                }
                array_push($words, implode(" ", $cCollect));
            }
            $all = array_merge($all, $words);
        }

        return array_reverse($all);
    }
}

if (!function_exists('delete_directory')) {
    /**
     * Empty and remove a directory
     *
     * @param string $dir
     *
     * @return boolean
     */
    function delete_directory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!delete_directory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($dir);
    }
}

if (!function_exists('items_in_folder')) {
    /**
     * Get all items in folder
     * 
     * @param string $folder
     * @param bool $included
     * 
     * @return array
     */
    function items_in_folder(string $folder, bool $included = true)
    {
        $dir = new \RecursiveDirectoryIterator(
            $folder,
            \FilesystemIterator::SKIP_DOTS
        );

        $iterators  = new \RecursiveIteratorIterator(
            $dir,
            \RecursiveIteratorIterator::SELF_FIRST
        );

        $items = [];
        foreach ($iterators as $file_info) {
            if (
                $file_info->isFile()
                && $file_info !== basename(__FILE__)
                && $file_info->getFilename() != '.gitignore'
            ) {
                $path = !empty($iterators->getSubPath())
                    ? $iterators->getSubPath() . DIRECTORY_SEPARATOR . $file_info->getFilename()
                    : $file_info->getFilename();
                $items[] = ($included ? $folder . DIRECTORY_SEPARATOR : '') . $path;
            }
        }

        return $items;
    }
}

if(!function_exists('get_client_ip')) {
    /**
     * Get client ip
     */
    function get_client_ip() {
        $ip = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ip = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ip = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ip = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ip = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ip = getenv('REMOTE_ADDR');
        else
            $ip = 'UNKNOWN';
        return $ip;
    }
}