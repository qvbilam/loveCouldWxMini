<?php


namespace App\Common\Model\Mysql;

use EasySwoole\Component\Singleton;

class VideoStyle extends MysqlBase
{
    use Singleton;
    public $table = 'video_style';
}