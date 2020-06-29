<?php

namespace App\Common\Model\Mysql;

use EasySwoole\Component\Singleton;

class LiveBanner extends MysqlBase
{
    use Singleton;
    public $table = 'live_banner';

}