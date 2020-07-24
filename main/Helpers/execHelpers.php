<?php

if (!function_exists('readDataViews')) {
    function readDataViews($folder)
    {
        $appPath = dirname(dirname(dirname(__FILE__)));
        $dataViews = array_filter(scandir("$appPath$folder"), function ($view) {
            return $view !== '.' && $view !== '..';
        });
        foreach ($dataViews as $item) {
            if (strpos($item, '.php') !== false) {
                writeCache($folder, $item);
            } else {
                readDataViews("$folder/$item");
            }
        }
    }
}

if (!function_exists('compileWatchingViews')) {
    function compileWatchingViews($view)
    {
        $folder = explode('/', $view);
        $file = array_pop($folder);
        $folder =  implode('/', $folder);
        writeCache($folder, $file);
    }
}

if (!function_exists('writeCache')) {
    function writeCache($folder, $file)
    {
        $appPath = dirname(dirname(dirname(__FILE__)));
        $data = file_get_contents("$appPath/$folder/$file");
        $data = str_replace('{{', '<?php', $data);
        $data = str_replace('}}', '?>', $data);
        $fullDir = config('app.base') .  '/cache';
        foreach (explode('/', $folder) as $f) {
            if($f != '') {
                $fullDir .= $f;
            }
            if (is_dir($fullDir) !== 1) {
                @mkdir($fullDir, 0777, true);
                $fullDir .= '/';
            }
        }
        if (!is_dir($fullDir)) {
            mkdir($fullDir, 0777);
        }
        $myfile = fopen("$fullDir/$file", "w") or die("Unable to open file!");
        fwrite($myfile, $data);
        fclose($myfile);
    }
}

if (!function_exists('execClearCache')) {
    function execClearCache()
    {
        $cachePath = __DIR__ . '/../../cache';
        foreach (scandir($cachePath) as $file) {
            if ($file != '.' && $file != '..' && $file != '.gitignore') {
                exec("rm -rf $cachePath/$file");
                // unlink("cache/$file");
            }
        }
        (new Main\Colors)->printSuccess("Configuration cache cleared!");
    }
}

if (!function_exists('execWriteCache')) {
    function execWriteCache()
    {
        $env = readDotENV();
        $cachePath = __DIR__ . '/../../cache';
        $myfile = fopen("$cachePath/environments.php", "w") or die("Unable to open file!");
        fwrite($myfile, "<?php\n");
        fwrite($myfile, "return array(\n");
        foreach ($env as $key => $value) {
            $key = trim($key);
            $value = trim($value);
            fwrite($myfile, "    '{$key}' => '{$value}',\n");
        }
        fwrite($myfile, ");");
    }
}

if (!function_exists('execWriteDataViews')) {
    function execWriteDataViews()
    {
        $resourcePath = '/resources';
        readDataViews("$resourcePath/views");
        (new Main\Colors)->printSuccess("Configuration cached successfully!");
    }
}
if (!function_exists('execWriteConfigCache')) {
    function execWriteConfigCache()
    {
        $cachePath = dirname(dirname(dirname(__FILE__))) . '/cache';
        foreach (scandir('config') as $file) {
            if (strlen($file) > 5) {
                $config = include './config/' . $file;
                $myfile = fopen("$cachePath/$file", "w") or die("Unable to open file!");
                fwrite($myfile, "<?php\n");
                fwrite($myfile, "return array(\n");
                foreach ($config as $key => $value) {
                    if (is_array($value)) {
                        _handleArrayConfig($key, $myfile, $value);
                    } else {
                        fwrite($myfile, "'$key' => '{$value}',\n");
                    }
                }
                fwrite($myfile, ");");
            }
        }
    }
}

if (!function_exists('_handleArrayConfig')) {
    function _handleArrayConfig($key, $myfile, array $values)
    {
        fwrite($myfile, "'{$key}' => array(\n");
        foreach ($values as $k => $v) {
            if (is_array($v)) {
                _handleArrayConfig($k, $myfile, $v);
            } else {
                fwrite($myfile, "        '{$k}' => '{$v}',\n");
            }
        }
        fwrite($myfile, "    ),\n");
    }
}

if (!function_exists('execMigrate')) {
    function execMigrate()
    {
        foreach (scandir('database/migration', 1) as $file) {
            if (strlen($file) > 5) {
                include './database/migration/' . $file;
                $classes = get_declared_classes();
                $class = end($classes);
                $object = new $class;
                if (method_exists($object, 'up')) {
                    (new Main\Colors)->printSuccess("Migrating: $class");
                    $object->up();
                    (new Main\Colors)->printSuccess("Migrated: $class");
                }
            }
        }
    }
}

if (!function_exists('execMigrateRollback')) {
    function execMigrateRollback()
    {
        $files = scandir('database/migration', 1);
        arsort($files);
        foreach ($files as $file) {
            if (strlen($file) > 5) {
                include './database/migration/' . $file;
                $classes = get_declared_classes();
                $class = end($classes);
                $object = new $class;
                if (method_exists($object, 'down')) {
                    (new Main\Colors)->printSuccess("Rolling back: $class");
                    $object->down();
                    (new Main\Colors)->printSuccess("Rolled back: $class");
                }
            }
        }
    }
}

if (!function_exists('execCreateServerCli')) {
    function execCreateServerCli($argv)
    {
        $host = '127.0.0.1';
        $port = '8000';
        $open = false;
        foreach ($argv as $param) {
            if (strpos($param, '-h=') !== false || strpos($param, '--host=') !== false) {
                $host = str_replace('--host=', '', $param);
            }
            if (strpos($param, '-p=') !== false || strpos($param, '--port=') !== false) {
                $port = str_replace('--port=', '', $param);
            }
            if (strpos($param, '-o') !== false || strpos($param, '--open') !== false) {
                $open = true;
            }

        }
        (new Main\Colors)->printSuccess("Starting development at: http://{$host}:{$port} \nUsing argument --open to open server on browser.");
        if ($open) {
            exec("open " . "http://{$host}:{$port}");
        }
        system("php -S {$host}:{$port} server.php");
    }
}

if (!function_exists('execGenerateKey')) {
    function execGenerateKey()
    {
        $env = '.env';
        $file_contents = file_get_contents($env);
        $each = explode("\n", $file_contents);
        $file = fopen($env, 'w');
        for ($i = 0; $i <= count($each) - 1; $i++) {
            if ($i == count($each) - 1) {
                if (strlen($each[$i]) <= 0) {
                    continue;
                }
            }
            $value = $each[$i];
            if (strpos($value, 'APP_KEY') !== false) {
                $value = 'APP_KEY=' . str_replace('=', '', base64_encode(microtime(true)));
            }
            fwrite($file, $value . "\n");
        }
        fclose($file);
        (new Main\Colors)->printSuccess("Generate key successfully.");
    }
}

if (!function_exists('execRunSeed')) {
    function execRunSeed()
    {
        (new Main\Colors)->printSuccess("Seeded successfully.");
    }
}

if (!function_exists('execMakeController')) {
    function execMakeController($name)
    {
        $paseController = explode('/', $name);
        $namespace = ';';
        $fullDir = 'app/Http/Controllers/';
        if (count($paseController) > 1) {
            $controller = array_pop($paseController);
            $namespace = '\\' . implode("\\", $paseController) . ';';
            foreach ($paseController as $dir) {
                $fullDir .= "{$dir}";
                if (is_dir($fullDir) !== 1) {
                    @mkdir($fullDir, 0777, true);
                    $fullDir .= '/';
                }
            }
        } else {
            $controller = $name;
        }
        $defaultControllerPath = dirname(__FILE__) . '/Init/controller.txt';
        $defaultController = file_get_contents($defaultControllerPath);
        $defaultController = str_replace(':namespace', $namespace, $defaultController);
        $defaultController = str_replace(':controller', $controller, $defaultController);
        $needleController = "{$fullDir}$controller.php";
        if (!file_exists($needleController)) {
            $myfile = fopen($needleController, "w") or die("Unable to open file!");
            fwrite($myfile, $defaultController);
            fclose($myfile);
            (new Main\Colors)->printSuccess("Created controller {$controller}");
        } else {
            (new Main\Colors)->printWarning("Controller {$needleController} already exists");
        }
        return true;
    }
}

if (!function_exists('execMakeModel')) {
    function execMakeModel($name)
    {
        $paseModel = explode('/', $name);
        $namespace = ';';
        $fullDir = 'app/Models/';
        if (count($paseModel) > 1) {
            $model = array_pop($paseModel);
            $namespace = '\\' . implode("\\", $paseModel) . ';';
            foreach ($paseModel as $dir) {
                $fullDir .= "{$dir}";
                if (is_dir($fullDir) !== 1) {
                    @mkdir($fullDir, 0777, true);
                    $fullDir .= '/';
                }
            }
        } else {
            $model = $name;
        }
        $defaultModelPath = dirname(__FILE__) . '/Init/model.txt';
        $defaultModel = file_get_contents($defaultModelPath);
        $defaultModel = str_replace(':namespace', $namespace, $defaultModel);
        $defaultModel = str_replace(':model', $model, $defaultModel);
        $needleModel = "{$fullDir}$model.php";
        if (!file_exists($needleModel)) {
            $myfile = fopen($needleModel, "w") or die("Unable to open file!");
            fwrite($myfile, $defaultModel);
            fclose($myfile);
            (new Main\Colors)->printSuccess("Created model {$model}");
        } else {
            (new Main\Colors)->printWarning("Model {$needleModel} already exists");
        }
        return true;
    }
}

if (!function_exists('execMakeMigration')) {
    function execMakeMigration($table)
    {
        $defaultMigratePath = dirname(__FILE__) . '/Init/migrate.txt';
        $defaultMigrate = file_get_contents($defaultMigratePath);
        $defaultMigrate = str_replace(':table', $table, $defaultMigrate);
        $defaultMigrate = str_replace(':Table', ucfirst($table), $defaultMigrate);
        $fullDir = 'database/migration/';
        $date = date('Ymd_His');
        $name = "{$date}_{$table}_migration.php";
        $needleTable = "{$fullDir}$name";
        if (!file_exists($needleTable)) {
            $myfile = fopen($needleTable, "w") or die("Unable to open file!");
            fwrite($myfile, $defaultMigrate);
            fclose($myfile);
            (new Main\Colors)->printSuccess("Created Table {$table}");
        } else {
            (new Main\Colors)->printWarning("Table {$needleTable} already exists");
        }
        return true;
    }
}


if (!function_exists('execMakeRequest')) {
    function execMakeRequest($request)
    {
        $paseRequest = explode('/', $request);
        $namespace = ';';
        $fullDir = 'app/Http/Requests/';
        if (count($paseRequest) > 1) {
            $request = array_pop($paseRequest);
            $namespace = '\\' . implode("\\", $paseRequest) . ';';
            foreach ($paseRequest as $dir) {
                $fullDir .= "{$dir}";
                if (is_dir($fullDir) !== 1) {
                    mkdir($fullDir, 0777, true);
                    $fullDir .= '/';
                }
            }
        }
        $defaultRequestPath = dirname(__FILE__) . '/Init/request.txt';
        $defaultRequest = file_get_contents($defaultRequestPath);
        $defaultRequest = str_replace(':request', $request, $defaultRequest);
        $defaultRequest = str_replace(':namespace', $namespace, $defaultRequest);
        $defaultRequest = str_replace(':Request', ucfirst($request), $defaultRequest);
        $name = "{$request}.php";
        $needleRequest = "{$fullDir}$name";
        if (!file_exists($needleRequest)) {
            $myfile = fopen($needleRequest, "w") or die("Unable to open file!");
            fwrite($myfile, $defaultRequest);
            fclose($myfile);
            (new Main\Colors)->printSuccess("Created Request {$request}");
        } else {
            (new Main\Colors)->printWarning("Request {$needleRequest} already exists");
        }
        return true;
    }
}
