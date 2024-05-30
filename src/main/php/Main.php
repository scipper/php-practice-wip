<?php

namespace Mys;

use Mys\test\Module;

class Main
{
    public static function main(): void
    {
        echo "Hello World!\n";

        $files = glob(realpath(".") . "/**/*Module.php");

        if (count($files) > 0) {
            foreach ($files as $file) {
                $info = pathinfo($file);
                print_r($info);
                $classPath = $info["dirname"] . "/" . $info["basename"];
                $className = $info["filename"];
                $c = require_once($classPath);
                if($className instanceof Module) {
                    /**
                     * @var Module $module
                     */
                    $module = new $info["filename"]();
                    print_r($module->getClasses());
                }
            }
        }
    }
}

Main::main();
