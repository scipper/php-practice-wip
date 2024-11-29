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

    public static function get415(): string {
        return <<<EOJ
{
    "statusCode": 415,
    "statusText": "Unsupported Media Type",
    "errorMessage": "Unsupported Media Type",
    "contentType": "application\/json"
}
EOJ;
    }

    public static function get406(): string {
        return <<<EOJ
{
    "statusCode": 406,
    "statusText": "Not Acceptable",
    "errorMessage": "Not Acceptable",
    "contentType": "application\/json"
}
EOJ;
    }

    public static function get200(): string {
        return <<<EOJ
{"message":"success"}
EOJ;
    }
}