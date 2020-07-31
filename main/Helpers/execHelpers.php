<?php

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
        $data = file_get_contents(BASE . "/$folder/$file");
        $data = str_replace('{{', '<?php', $data);
        $data = str_replace('}}', '?>', $data);
        $fullDir = BASE .  '/cache';
        foreach (explode('/', $folder) as $f) {
            if ($f != '') {
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

if (!function_exists('execMigrate')) {
    function execMigrate()
    {
        foreach (scandir(BASE . '/database/migration', 1) as $file) {
            if (strlen($file) > 5) {
                include BASE . '/database/migration/' . $file;
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
        $files = scandir(BASE . '/database/migration', 1);
        arsort($files);
        foreach ($files as $file) {
            if (strlen($file) > 5) {
                include BASE . '/database/migration/' . $file;
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

if (!function_exists('execRunSeed')) {
    function execRunSeed()
    {
        (new Main\Colors)->printSuccess("Seeded successfully.");
    }
}

if (!function_exists('execMakeController')) {
    
}

if (!function_exists('execMakeModel')) {
    
}

if (!function_exists('execMakeMigration')) {
    function execMakeMigration($table)
    {
        $defaultMigratePath = BASE. '/main/Helpers/Init/migrate.txt';
        $defaultMigrate = file_get_contents($defaultMigratePath);
        $defaultMigrate = str_replace(':table', $table, $defaultMigrate);
        $defaultMigrate = str_replace(':Table', ucfirst($table), $defaultMigrate);
        $fullDir = BASE . '/database/migration/';
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
    
}
