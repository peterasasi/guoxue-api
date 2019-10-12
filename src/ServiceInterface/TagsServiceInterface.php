<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\ServiceInterface;

use App\Entity\Tags;
use Dbh\SfCoreBundle\Common\BaseServiceInterface;

interface TagsServiceInterface extends BaseServiceInterface
{
    public function addNotExists(Tags $tags);
}
