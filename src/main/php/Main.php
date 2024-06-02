<?php declare(strict_types = 1);

namespace Mys;

use Mys\Api\PingApi;

class Main
{
    public static function main(): void
    {
        require "../../../vendor/autoload.php";
        $reflector = new \ReflectionClass(PingApi::class);
        $attrs = $reflector->getAttributes();

        foreach ($attrs as $attribute)
        {
            var_dump($attribute->getName());
            var_dump("\n");
            var_dump($attribute->getArguments());
            var_dump("\n");
            var_dump($attribute->newInstance());
        }
//        $logger = new PrintLogger();
//        $injector = new DependencyInjector($logger);
//        $moduleListText = file_get_contents("./module-list.txt");
//        $moduleList = [];
//        if ($moduleListText)
//        {
//            array_push($moduleList, ...explode("\n", $moduleListText));
//        }
//        $application = new Application($injector, $moduleList, $logger);
//        $application->init();
//
//        try
//        {
//            /**
//             * @var WelcomeComponent $class
//             */
//            $class = $injector->get(WelcomeComponent::class);
//            $class->printWelcomeMessage();
//        }
//        catch (ClassNotFoundException|CyclicDependencyDetectedException $e)
//        {
//            $logger->error($e);
//        }
    }
}

Main::main();
