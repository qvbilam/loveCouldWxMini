<?php

namespace App\Common\Model\Mysql;

use EasySwoole\Component\Singleton;

class Video extends MysqlBase
{
    use Singleton;
    public $table = 'video';
}