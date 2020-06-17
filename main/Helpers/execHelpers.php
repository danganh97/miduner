<?php

if (!function_exists('readDataViews')) {
    function readDataViews($folder)
    {
        $dataViews = array_filter(scandir("resources/$folder"), function ($view) {
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

if (!function_exists('writeCache')) {
    function writeCache($folder, $file)
    {
        $data = file_get_contents("resources/$folder/$file");
        $data = str_replace('{{', '<?php', $data);
        $data = str_replace('}}', '?>', $data);
        if (!is_dir("cache/$folder")) {
            mkdir("cache/$folder", 0777);
        }
        $myfile = fopen("cache/$folder/$file", "w") or die("Unable to open file!");
        fwrite($myfile, $data);
        fclose($myfile);
    }
}

if (!function_exists('execClearCache')) {
    function execClearCache()
    {
        foreach (scandir('cache') as $file) {
            if ($file != '.' && $file != '..' && $file != '.gitignore') {
                exec("rm -rf cache/$file");
                // unlink("cache/$file");
            }
        }
        system("echo " . 'Configuration cache cleared!');
    }
}

if (!function_exists('execWriteCache')) {
    function execWriteCache()
    {
        $env = readDotENV();
        $myfile = fopen("cache/environments.php", "w") or die("Unable to open file!");
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

if (!function_exists('execWriteConfigCache')) {
    function execWriteConfigCache()
    {
        foreach (scandir('config') as $file) {
            if (strlen($file) > 5) {
                $config = include './config/' . $file;
                $myfile = fopen("cache/$file", "w") or die("Unable to open file!");
                fwrite($myfile, "<?php\n");
                fwrite($myfile, "return array(\n");
                foreach ($config as $key => $value) {
                    fwrite($myfile, "    '{$key}' => ");
                    if (is_array($value)) {
                        fwrite($myfile, "array(\n");
                        foreach ($value as $k => $v) {
                            fwrite($myfile, "        '{$k}' => '{$v}',\n");
                        }
                        fwrite($myfile, "    ),\n");
                    } else {
                        fwrite($myfile, "'{$value}',\n");
                    }
                }
                fwrite($myfile, ");");
            }
        }
    }
}

if(!function_exists('execMigrate')) {
    function execMigrate()
    {
        foreach (scandir('database/migration', 1) as $file) {
            if (strlen($file) > 5) {
                include './database/migration/' . $file;
                $classes = get_declared_classes();
                $class = end($classes);
                system("echo " . 'Migrating: ' . $class);
                $object = new $class;
                system("echo " . 'Migrated: ' . $class);
            }
        }
    }
}

if(!function_exists('execCreateServerCli')) {
    function execCreateServerCli($argv)
    {
        $host = '127.0.0.1';
        $port = '8000';
        foreach ($argv as $param) {
            if (strpos($param, '--host=') !== false) {
                $host = str_replace('--host=', '', $param);
            }
            if (strpos($param, '--port=') !== false) {
                $port = str_replace('--port=', '', $param);
            }
        }
        system("echo " . "Starting development at: http://{$host}:{$port}");
        exec("open " . "http://{$host}:{$port}");
        system("php -S {$host}:{$port} server.php");
    }
}

if(!function_exists('execGenerateKey')) {
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
        system("echo " . 'Key generated !');
    }
}

if(!function_exists('execRunSeed')) {
    function execRunSeed()
    {
        system("echo " . 'Seeded');
    }
}