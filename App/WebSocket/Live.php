<?php

namespace App\WebSocket;

use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\EasySwoole\Task\TaskManager;
use EasySwoole\Socket\AbstractInterface\Controller;
use App\Common\Lib\FdManager;
use App\Common\Model\Mysql\User as UserModel;
use EasySwoole\Task\Task;


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
        if (empty($data) || empty($data['room_id']) || empty($data['user_id'])) {
            return $this->response()->setMessage(json_encode([
                'code' => -1,
                'msg' => '滚蛋,进入直播间失败'
            ])); // 进入直播聊天失败 缺少参数
        }
        $server = ServerManager::getInstance()->getSwooleServer();
        // 内存绑定 房间号->连接号,  连接号<->用户id
        FdManager::getInstance()->bindUser($this->caller()->getClient()->getFd(), $data['user_id'], $data['room_id']);
        if (!empty($data['vip']) && $data['vip'] > 0) {
            foreach (FdManager::getInstance()->userIdFd as $v) { // 广播当前房间消息
                // 加特效,欢迎大佬进入直播间
                if ($v['roomId'] == $data['room_id']) { // 同一房间内才会提示
                    $server->push($v['fd'], json_encode([
                        'code' => 0,
                        'msg' => 'ok',
                        'content' => [
                            'user_id' => $data['user_id'],
                            'nick_name' => $data['nick_name'],
                            'vip' => $data['vip']
                        ]
                    ]));
                }
            }
        }

    }

    /*
     * 群聊
     * {
        "class":"live",
        "action":"chat",
        "content":{
            "room_id":1,   // 房间号
            "user_id":1,    // 用户id
            "send_type":0, // 发送类型:-1私聊,0全体,1房间系统消息
            "type":0,      // 消息类型:0文本,1图片,2语音,3视频
            "style_id":0,  // 气泡样式
            "content":"你好 " // 发送内容
            }
        }
     * */
    public function chat()
    {
        $data = $this->caller()->getArgs()['content'];
        if (!isset($data['room_id']) || !isset($data['send_type']) || !isset($data['user_id'])) {
            return $this->response()->setMessage(json_encode([
                'code' => -1,
                'msg' => '消息发送失败'
            ]));
        }
        if (empty($data['content'])) {
            return $this->response()->setMessage(json_encode([
                'code' => -1,
                'msg' => '消息不能为空'
            ]));
        }
        switch ($data['send_type']) { // 发送类型
            case -1:
                self::chatProtected($data);
                break;
            case 0:
                self::chatPublic($data);
                break;
            case 1:
                break;
        }
    }


    public function who()
    {
        $this->response()->setMessage(json_encode([
            'code' => 0,
            'msg' => 'ok',
            'content' => [
                'fd' => $this->caller()->getClient()->getFd()
            ],
        ]));
    }


    // 群聊
    static protected function chatPublic($data)
    {
        $userId = $data['user_id'];
        $nickName = $data['nick_name'] ?: '游客';
        $vip = $data['vip'] ?: 0;
        $roomId = $data['room_id'] ?: 0;
        $type = $data['type'] ?: 0;
        $content = $data['content'] ?: '';
        $styleId = $data['style_id'] ?: 0;
        // 异步推送
        TaskManager::getInstance()->async(function () use ($userId, $nickName, $vip, $roomId, $type, $content, $styleId) {
            $server = ServerManager::getInstance()->getSwooleServer();
            foreach (FdManager::getInstance()->userIdFd as $v) {
                if ($v['roomId'] == $roomId) { // 同一房间内才会提示
                    $server->push($v['fd'], json_encode([
                        'code' => 0,
                        'msg' => 'ok',
                        'content' => [
                            'user_id' => $userId,
                            'nick_name' => $nickName,
                            'vip' => $vip,
                            'type' => $type,
                            'style_id' => $styleId,
                            'content' => $content,
                            'send_type' => 0
                        ]
                    ]));
                }
            }
        });
    }

    // 私聊
    static protected function chatProtected($data)
    {
        $userId = $data['user_id'];
        $adoptId = $data['adopt_id'];
        $nickName = $data['nick_name'] ?: '游客';
        $vip = $data['vip'] ?: 0;
        $roomId = $data['room_id'] ?: 0;
        $type = $data['type'] ?: 0;
        $content = $data['content'];
        $styleId = isset($data['style_id']) ? $data['style_id'] : 0;
        // 异步推送
        TaskManager::getInstance()->async(function () use ($userId, $adoptId, $nickName, $roomId, $content, $styleId, $type, $vip) {
            $server = ServerManager::getInstance()->getSwooleServer();
            $adoptIdFd = FdManager::getInstance()->userIdFd($adoptId);
            $sendData = [
                'code' => 0,
                'msg' => 'ok',
                'content' => [
                    'user_id' => $userId,
                    'nick_name' => $nickName,
                    'vip' => $vip,
                    'type' => $type,
                    'style_id' => $styleId,
                    'content' => $content,
                    'send_type' => -1
                ]
            ];
            $server->push($adoptIdFd, json_encode($sendData)); // 用户发送消息
            $server->push(FdManager::getInstance()->userIdFd($userId), json_encode($sendData)); // 发送用户告知发送成功
        });
    }


}