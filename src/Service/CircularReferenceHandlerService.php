<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Service;

use Dbh\SfCoreBundle\Common\BaseService;


/**
 * json格式化的时循环引用问题
 * Class CircularReferenceHandlerService
 * @package App\Service
 */
class CircularReferenceHandlerService
{
    public function __invoke($object)
    {
        if (method_exists($object, 'getId')) {
            $title = '';
            if (method_exists($object, 'getTitle')) {
                $title = $object->getTitle();
            }
            return [
                'id' => $object->getId(),
                'title' => $title
            ];
        }
        return [];
    }
}
