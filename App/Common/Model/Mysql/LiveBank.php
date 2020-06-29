<?php

namespace App\Common\Model\Mysql;

use EasySwoole\Component\Singleton;

class LiveBank extends MysqlBase
{
    use Singleton;
    public $table = 'live_bank';

}