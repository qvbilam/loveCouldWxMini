<?php


namespace App\HttpController\Api;

use App\Common\Model\Mysql\LiveBanner as BannerModel;

class Banner extends ApiBase
{
    // 获取banner
    public function index()
    {
        $data = BannerModel::getInstance()->getByConditon(['state' => 1], 'image', 5, ['`order`', 'desc']);
        return $this->success($data);
    }
}