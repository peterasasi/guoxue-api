<?php
/**
 * Created by PhpStorm.
 * User: asasi
 * Date: 2018/8/9
 * Time: 15:35
 */

namespace App\ServiceInterface;


use by\infrastructure\base\CallResult;

interface SecurityCodeServiceInterface
{
    public function isLegalCode($code, $accepter, $type, $client_id, $is_clear = true):CallResult;
    public function isLegalById($verifyId, $verifyCode, $accepter, $type, $client_id, $is_clear = true):CallResult;
}
