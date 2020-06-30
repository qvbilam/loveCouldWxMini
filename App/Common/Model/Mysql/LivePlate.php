<?php

namespace App\Common\Model\Mysql;

use EasySwoole\Component\Singleton;

class LivePlate extends MysqlBase
{
    use Singleton;
    public $table = 'live_plate';

}