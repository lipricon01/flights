<?php
/**
 * Created by PhpStorm.
 * User: vladislavkim
 * Date: 5/10/19
 * Time: 20:17
 */

namespace App\Helpers;



use Psr\Http\Message\ResponseInterface;

class FormatterHelper
{
    public static function formatBody(ResponseInterface $response)
    {
        $result = json_decode((string)$response->getBody());
        return $result;

    }
}