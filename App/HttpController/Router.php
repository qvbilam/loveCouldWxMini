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
        $routeCollector->addRoute('POST', '/api/register', '/Api/Login/register');              // 注册
        $routeCollector->addRoute('POST', '/api/login', '/Api/Login/login');                    // 登录
        /****************************************  直播模块  ****************************************/
        $routeCollector->addRoute('GET', '/api/live_bank', '/Api/Live/Bank/index');               // 获取大板块 包含部分子板块
        $routeCollector->addRoute('GET', '/api/live_bank/{bank_id}', '/Api/Live/Bank/getSonBank');// 获取所有子板块
        $routeCollector->addRoute('GET', 'api/live_room/{room_id}', 'Api/Live/Live/getData');     // 直播数据
        /****************************************  视频模块  ****************************************/

        /***************************************  验证码模块  ***************************************/
        $routeCollector->addRoute('GET', '/api/register_code', '/Api/Sms/register');


    }
}