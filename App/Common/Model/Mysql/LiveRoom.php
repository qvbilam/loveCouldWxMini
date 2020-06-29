<?php

namespace App\Common\Model\Mysql;

use EasySwoole\Component\Singleton;

class LiveRoom extends MysqlBase
{
    use Singleton;
    public $table = 'live_room';
}