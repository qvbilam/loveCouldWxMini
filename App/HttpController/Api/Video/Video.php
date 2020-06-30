<?php

namespace App\HttpController\Api\Video;

use App\HttpController\Api\ApiBase;
use App\Common\Model\Mysql\Video as VideoModel;
use App\Common\Model\Mysql\VideoPlate as VideoPlateModel;
use App\Common\Model\Mysql\VideoStyle as VideoStyleModel;
use App\Common\Model\Mysql\VideoDetail as VideoDetailModel;
use App\Common\Model\Mysql\VideoPlace as VideoPlaceModel;

class Video extends ApiBase
{
    // 获取视频
    public function getNormalVideo()
    {
        $where = ['delete_time' => 0];
        // 板块id
        if (!empty($this->params['plate_id'])) {
            $where['plate_id'] = $this->params['plate_id'];
        }
        // 风格id
        if (!empty($this->params['style_id'])) {
            $where['style_id'] = $this->params['style_id'];
        }
        // 产地id
        if (!empty($this->params['place_id'])) {
            $where['place_id'] = $this->params['place_id'];
        }
        $fields = 'id,plate_id,style_id,place_id,name,content,total_num,image';
        $res = VideoModel::getInstance()->getPaginationByConditon($where, $fields, $this->params['size'], $this->params['page'], ['`order`', 'desc']);
        if (!empty($res['list'])) {
            foreach ($res['list'] as &$v) {
                $v['plate_name'] = VideoPlateModel::getInstance()->getValue(['id' => $v['plate_id']], 'name');
                $v['style_name'] = VideoStyleModel::getInstance()->getValue(['id' => $v['style_id']], 'name');
                $v['place_name'] = VideoPlaceModel::getInstance()->getValue(['id' => $v['place_id']], 'name');
            }
        }
        return $this->success($res);
    }

    // 获取详情
    public function getNormalDetail()
    {
        if (empty($this->params['id'])) {
            return $this->error('获取详情失败');
        }
        $where = ['delete_time' => 0, 'id' => $this->params['id']];
        $fields = 'id,plate_id,style_id,place_id,name,content,total_num,image';
        $res = VideoModel::getInstance()->getByConditon($where, $fields, 1);
        if (!empty($res)) {
            $res['plate_name'] = VideoPlateModel::getInstance()->getValue(['id' => $res['plate_id']], 'name', 1);
            $res['style_name'] = VideoStyleModel::getInstance()->getValue(['id' => $res['style_id']], 'name', 1);
            $res['place_name'] = VideoPlaceModel::getInstance()->getValue(['id' => $res['place_id']], 'name', 1);
            $res['detail'] = VideoDetailModel::getInstance()->getByConditon(['video_id' => $res['id'], 'delete_time' => 0], 'id as video_detail_id,name as video_detail_name,num as video_detail_num');
        }
        return $this->success($res);
    }

}