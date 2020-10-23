<?php

namespace App\Common\Lib;

use EasySwoole\Component\Singleton;
use Swoole\Table;

class FdManager
{
    use Singleton;

    public $fdUserId;   // 连接id 关联用户id
    public $userIdFd;   // 用户id关联连接id, 可直接遍历获取连接用户

    public function __construct($size = 1024 * 256)
    {
        $this->fdUserId = new Table($size);
        $this->fdUserId->column('userId', Table::TYPE_INT, 25);
        $this->fdUserId->create();
        $this->userIdFd = new Table($size);
        $this->userIdFd->column('fd', Table::TYPE_INT, 10);
        $this->userIdFd->column('roomId', Table::TYPE_INT, 10);
        $this->userIdFd->create();
    }

    // 绑定连接用户
    public function bindUser(int $fd, int $userId, int $roomId)
    {
        $this->fdUserId->set($fd, ['userId' => $userId]);
        $this->userIdFd->set($userId, ['fd' => $fd, 'roomId' => $roomId]);
    }

    // 清除内存
    public function delete(int $fd)
    {
        $userId = $this->fdUserId($fd);
        if ($userId) {
            $this->userIdFd->del($userId);
        }
        $this->fdUserId->del($fd);
    }

    // 通过连接id获取用户id
    public function fdUserId(int $fd): ?Random
    {
        $ret = $this->fdUserId->get($fd);
        if ($ret) {
            return $ret['userId'];
        } else {
            return null;
        }
    }

    // 通过用户id获取连接id
    public function userIdFd(int $userId): ?int
    {
        $ret = $this->userIdFd->get($userId);
        if ($ret) {
            return $ret['fd'];
        } else {
            return null;
        }
    }


}