<?php
/**
 * Created by PhpStorm.
 * User: qvbilam
 * Date: 2020-06-09
 * Time: 13:45
 */

namespace App\Common\Model\Mysql;

use EasySwoole\Component\Singleton;

class User extends MysqlBase
{
    use Singleton;
    public $table = 'user';
}