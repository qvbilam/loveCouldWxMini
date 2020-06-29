<?php

namespace App\Common\Lib;

use App\Common\Lib\phoneSms;

class SendSms
{
    static public function sendPhoneSms($mobile, $code)
    {
        $options['accountsid'] = 'fdd76caa9175ab6d30da2e281e1372ee';
        $options['token'] = '61995aa241a28e7efed581f92809b9eb';
        $ucpass = new phoneSms($options);
        $appid = "4bebc7fea3084c4cb2fe8be1c262d8dd";    //应用的ID，可在开发者控制台内的短信产品下查看
        $templateid = "272080";    //可在后台短信产品→选择接入的应用→短信模板-模板ID，查看该模板ID
        $param = $code; //多个参数使用英文逗号隔开（如：param=“a,b,c”），如为参数则留空
        //70字内（含70字）计一条，超过70字，按67字/条计费，超过长度短信平台将会自动分割为多条发送。分割后的多条短信将按照具体占用条数计费。
        $res = $ucpass->SendSms($appid, $templateid, $param, $mobile, $uid = '');
        return $res;
    }
}