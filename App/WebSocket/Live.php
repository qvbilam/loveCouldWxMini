<?php

namespace App\WebSocket;

use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\EasySwoole\Task\TaskManager;
use EasySwoole\Socket\AbstractInterface\Controller;
use App\Common\Lib\FdManager;


/*
 * 聊天室控制器
 * */

class Live extends Controller
{
    /*
     * 连接
     {
    "class":"live",
    "action":"connect",
    "content":{
        "room_id":1,    // 房间号
        "user_id":1,    // 用户id
        "nick_name":"qvbilam", // 昵称
        "vip":0 // vip等级
        }
    }
     * */
    public function connect()
    {
        $data = $this->caller()->getArgs()['content'];
        print_r($data);
        if (empty($data) || empty($data['room_id']) || empty($data['user_id'])) {
            $this->response()->setMessage('滚蛋,进入直播间失败');
        }
        if (!empty($data['vip']) && $data['vip'] > 0) {
            $server = ServerManager::getInstance()->getSwooleServer();
            foreach (FdManager::getInstance()->roomIdFd as $v) { // 广播当前房间消息
                // 加特效,欢迎大佬进入直播间
                $server->push($v['fd'], json_encode(['content' => '欢迎xxx进入直播间']));
            }
        }
        // 内存绑定 房间号->连接号,  连接号<->用户id
        FdManager::getInstance()->bindRoom($data['room_id'], $this->caller()->getClient()->getFd());
        FdManager::getInstance()->bindUser($this->caller()->getClient()->getFd(), $data['user_id']);
    }

    /*
     * 群聊
     * {
        "class":"live",
        "action":"chat_public",
        "content":{
            "room_id":1,   // 房间号
            "user_id":1,    // 用户id
            "send_type":0, // 发送类型:-1私聊,0全体,1系统消息
            "type":0,      // 消息类型:0文本,1图片,2语音,3视频
            "style_id":0,  // 气泡样式
            "content":"你好 " // 发送内容
            }
        }
     * */
    public function chat_public()
    {
        $data = $this->caller()->getArgs()['content'];
        print_r($data);
        $server = ServerManager::getInstance()->getSwooleServer();
        foreach (FdManager::getInstance()->roomIdFd as $v) {
            print_r($v);
            // todo 屏蔽的用户不广播 or 前端判断
            $server->push($v['fd'], 'send content:' . json_encode($this->caller()->getArgs())); // 所有房间返回发送消息内容
        }
    }

    /*
     * 私聊
     * */

    public function who()
    {
        $this->response()->setMessage($this->caller()->getClient()->getFd());
    }

    function delay()
    {
        $this->response()->setMessage('this is delay action');
        $client = $this->caller()->getClient();
        // 异步推送, 这里直接 use fd也是可以的
        TaskManager::getInstance()->async(function () use ($client) {
            $server = ServerManager::getInstance()->getSwooleServer();
            $i = 0;
            while ($i < 5) {
                sleep(1);
                $server->push($client->getFd(), 'push in http at ' . date('H:i:s'));
                $i++;
            }
        });
    }
}