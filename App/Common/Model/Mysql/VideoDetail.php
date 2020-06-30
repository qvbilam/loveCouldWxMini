<?php

namespace App\Common\Model\Mysql;

use EasySwoole\Component\Singleton;

class VideoDetail extends MysqlBase
{
    use Singleton;
    public $table = 'video_detail';
}