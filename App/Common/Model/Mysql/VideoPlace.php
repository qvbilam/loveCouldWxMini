<?php

namespace App\Common\Model\Mysql;

use EasySwoole\Component\Singleton;

class VideoPlace extends MysqlBase
{
    use Singleton;
    public $table = 'video_place';
}