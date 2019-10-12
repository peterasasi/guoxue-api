<?php
/**
 * Created by PhpStorm.
 * User: asasi
 * Date: 2018/8/13
 * Time: 14:56
 */

namespace App\Helper;


use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidatorErrorHelper
{
    public static function simplify(ConstraintViolationListInterface $errors) {
        if (count($errors) > 0) {
            foreach ($errors as $vo) {
                if ($vo instanceof ConstraintViolation) {
                    return $vo->getMessage();
                }
            }
        }

        return "";
    }
}
