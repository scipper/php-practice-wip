<?php declare(strict_types = 1);

namespace Mys\SystemTests\Router;

class JsonResponses {
    public static function get404(): string {
        return <<<EOJ
{
    "statusCode": 404,
    "statusText": "Not Found",
    "errorMessage": "Not Found",
    "contentType": "application\/json"
}
EOJ;
    }

    public static function get500($errorMessage): string {
        return <<<EOJ
{
    "statusCode": 500,
    "statusText": "Internal Server Error",
    "errorMessage": "$errorMessage",
    "contentType": "application\/json"
}
EOJ;
    }
}