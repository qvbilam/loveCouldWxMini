<?php

namespace App\HttpController;

use EasySwoole\Http\AbstractInterface\AbstractRouter;
use FastRoute\RouteCollector;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;

class Router extends AbstractRouter
{
    function initialize(RouteCollector $routeCollector)
    {
        /****************************************  用户模块  ****************************************/
        $routeCollector->addRoute('POST', '/api/register', '/Api/Login/register');                      // 注册
        $routeCollector->addRoute('POST', '/api/login', '/Api/Login/login');                            // 登录
        /****************************************  直播模块  ****************************************/
        $routeCollector->addRoute('GET', '/api/live_plate', '/Api/Live/Plate/index');                     // 获取大板块 包含部分子板块
        $routeCollector->addRoute('GET', '/api/live_plate/{plate_id}', '/Api/Live/Plate/getSonPlate');      // 获取所有子板块
        $routeCollector->addRoute('GET', '/api/live_room/{room_id}', 'Api/Live/Live/getData');          // 直播数据
        /****************************************  视频模块  ****************************************/
        $routeCollector->addRoute('GET', '/api/plate', 'Api/Video/Plate/getNormalPlate');                       // 获取板块
        $routeCollector->addRoute('GET', '/api/video', 'Api/Video/Video/getNormalVideo');                       // 获取视频类
        $routeCollector->addRoute('GET', '/api/video/{id}', 'Api/Video/Video/getNormalDetail');           // 获取视频类详情
        $routeCollector->addRoute('GET', '/api/video_detail/{id}', 'Api/Video/VideoDetail/getNormalVideo');// 获取视频详情
        $routeCollector->addRoute('GET', '/api/video_danmu/{video_id}', 'Api/Video/VideoDanmu/getNormalDanmuByVideoId');// 获取视频弹幕信息
        /***************************************  验证码模块  ***************************************/
        $routeCollector->addRoute('GET', '/api/register_code', '/Api/Sms/register');


    }
}