<?php

namespace common\helpers;

use common\logics\ConfigLogic;

class CurlHelper
{
    /**
     * 请求辅助函数
     *
     * @param       $url
     * @param       $data
     * @param int   $second
     * @param array $aHeader
     * @param array $option
     * @return mixed
     */
    public static function curlPost($url, $data, $second = 5, $aHeader = [], $option = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);     //超时时间
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        if (count($aHeader) >= 1) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
        }

        if ($option) {
            foreach ($option as $key => $val) {
                curl_setopt($ch, $key, $val);
            }
        }

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $result = curl_exec($ch);
        if (empty($result)) {
            $result['code'] = 1;
            $result['msg']  = curl_errno($ch) . ' - ' . curl_error($ch);
        }
        curl_close($ch);

        return $result;
    }


    /**
     * 文档地址： https://developers.dingtalk.com/document/robots/monitoring-and-alerting
     * @param        $content
     * @param string $signText 文本签名 [预警, 提醒, 报警] 必须是这几个类型，与在钉钉配置机器人时的一直，否则报错30001错误码
     * @param array $phone
     */
    public static function sendDingTalk($content, $signText = "提醒", $phone = [])
    {
        $accessToken = '*****钉钉开启机器人的时候配置';
        $webHook     = "https://oapi.dingtalk.com/robot/send?access_token=" . $accessToken;
        if ($phone) {
            $data['at']['atMobiles'] = $phone;
            $data['at']['isAtAll']   = false;
        }
        $data['msgtype'] = 'text';
        $comContent      = "【{$signText}】 " . "时间：" . date('Y-m-d H:i:s', time()) . "\n\n";
        if (is_string($content)) {
            $data['text']['content'] = $comContent . $content;
        } else {
            $data['text']['content'] = $comContent . stripslashes(json_encode($content, JSON_UNESCAPED_UNICODE));
        }
        $sendData = json_encode($data, JSON_UNESCAPED_UNICODE);
        $res      = self::curlPost($webHook, $sendData, 5,
            ["Content-Type: application/json;charset=utf-8"]);
        return $res;
    }

    /**
     * curl 请求方法
     * @param string $url       请求地址
     * @param array $body       请求主体(主体参数部分)
     * @param array $header     头部参数
     * @param string $method    请求方法(GET / POST / PUT / DELETE)
     * @param int $timeOut      请求超时时间(s)
     * @return mixed
     */
    public static function curlRequest($url, $body = [], $header = [], $method = "POST", $timeOut = 60){
        //1.创建一个curl资源
        $ch = curl_init();
        //2.设置URL和相应的选项
        curl_setopt($ch,CURLOPT_URL,$url);//设置url
        //1)设置请求头
        //设置为false,只会获得响应的正文(true的话会连响应头一并获取到)
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt ( $ch, CURLOPT_TIMEOUT,$timeOut); // 设置超时限制防止死循环
        //设置发起连接前的等待时间，如果设置为0，则无限等待。
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeOut);
        //将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //2)设备请求体
        if ($body) {
            //curl_setopt($ch, CURLOPT_POSTFIELDS, $body);//全部数据使用HTTP协议中的"POST"操作来发送。
            if(is_array($body) || is_object($body)){
                $body = http_build_query($body); //重组请求数据
            }else{
                if(ToolHelper::xmlParser($body)){
                    //xml数据
                    $header[] = 'Content-type: text/xml'; //添加xml类型
                }
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body); //更新于2017-12-12
        }
        //设置请求头
        if(count($header)>0){
            curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
        }
        //上传文件相关设置
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);// 从证书中检查SSL加密算
        //3)设置提交方式
        switch($method){
            case "GET":
                curl_setopt($ch,CURLOPT_HTTPGET,true);
                break;
            case "POST":
                curl_setopt($ch,CURLOPT_POST,true);
                break;
            case "PUT"://使用一个自定义的请求信息来代替"GET"或"HEAD"作为HTTP请求。这对于执行"DELETE" 或者其他更隐蔽的HTTP
                curl_setopt($ch,CURLOPT_CUSTOMREQUEST,"PUT");
                break;
            case "DELETE":
                curl_setopt($ch,CURLOPT_CUSTOMREQUEST,"DELETE");
                break;
        }
        //4)在HTTP请求中包含一个"User-Agent: "头的字符串。-----必设
        curl_setopt($ch, CURLOPT_USERAGENT, 'SSTS Browser/1.0');
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
        curl_setopt ( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)' ); // 模拟用户使用的浏览器
        //5)
        //3.抓取URL并把它传递给浏览器
        $output = curl_exec($ch);
        $curlInfo = curl_getinfo($ch);
        $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        $curlError = $output === false ? curl_error($ch) : '';
        //4.关闭curl资源，并且释放系统资源
        curl_close($ch);
        return [
            'http_code' => $httpCode,
            'output' => $output,
            'curl_info' => $curlInfo,
            'curl_error' => $curlError
        ];
    }

}