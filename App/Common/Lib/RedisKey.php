<?php
/**
 * Created by PhpStorm.
 * User: qvbilam
 * Date: 2020-06-09
 * Time: 13:18
 */

namespace App\Common\Lib;

class RedisKey
{
    static public $prefix = 'lc_';
    static public $sms_pre = 'sms_';
    static public $user_pre = 'user_';
    static public $live_pre = 'live_game_';
    static public $chat_pre = 'live_chat_';
    static public $data_pre = 'live_data_';

    /*手机验证码 redis key的 前缀的*/
    static public function smsKey($phone)
    {
        return self::$prefix . self::$sms_pre . $phone;
    }

    static public function userkey($phone)
    {
        return self::$prefix . self::$user_pre . $phone;
    }
}