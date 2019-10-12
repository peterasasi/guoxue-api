<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\ServiceInterface;

use App\Entity\Picture;
use Dbh\SfCoreBundle\Common\BaseServiceInterface;

interface PictureServiceInterface extends BaseServiceInterface
{
    public function safeInsert(Picture $entity);
}
