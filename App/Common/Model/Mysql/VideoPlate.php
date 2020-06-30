<?php

namespace App\Common\Model\Mysql;

use EasySwoole\Component\Singleton;

class VideoPlate extends MysqlBase
{
    use Singleton;
    public $table = 'video_plate';
}