<?php

namespace App\Common\Model\Redis;

use EasySwoole\Component\Di;
use EasySwoole\Component\Singleton;
use App\Common\Lib\CodeStatus;
use EasySwoole\Redis\Redis;

class RedisBase
{
    use Singleton;

    public $redis;

    public function __construct(...$data)
    {
        /*判断有没有安装redis拓展*/
        if (!extension_loaded('redis')) {
            throw new \Exception(CodeStatus::getReasonPhrase(CodeStatus::REDIS_LOADED_ERROR));
        }
        try {
            $redis = Di::getInstance()->get('REDIS');
            if ($redis instanceof Redis) {
                $this->redis = $redis;
            } else {
                $devConf = \EasySwoole\EasySwoole\Config::getInstance();
                $redisConf = new \EasySwoole\Redis\Config\RedisConfig($devConf->getConf("REDIS"));
                $this->redis = new Redis($redisConf);
            }
        } catch (\Exception $e) {
            throw new \Exception(CodeStatus::getReasonPhrase(CodeStatus::REDIS_CONNCE_ERROR));
        }
        if (!$this->redis) {
            throw new \Exception(CodeStatus::getReasonPhrase(CodeStatus::REDIS_CONNCE_ERROR));
        }
    }

}