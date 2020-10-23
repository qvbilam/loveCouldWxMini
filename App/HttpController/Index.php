<?php

namespace App\HttpController;

use App\HttpController\Api\ApiBase;
use App\Common\Model\Mysql\User as UserModel;

class Index extends ApiBase
{
    public function index()
    {
        $data = UserModel::getInstance()->getValue([],'id');
        return $this->success($data);
    }
}