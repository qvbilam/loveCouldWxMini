<?php

namespace App\Common\Model\Mysql;

use EasySwoole\Component\Singleton;

class VideoDanmu extends MysqlBase
{
    use Singleton;
    public $table = 'video_danmu';
}