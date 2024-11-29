<?php declare(strict_types = 1);

namespace Mys;

require __DIR__ . "/../../../vendor/autoload.php";

$logsFolder = __DIR__ . "/../../../logs";
$moduleListFile = __DIR__ . "/../resources/Modules/module-list.txt";
$response = Router::main($logsFolder, $moduleListFile);
echo $response;