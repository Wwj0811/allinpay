<?php

namespace allinpay;


use allinpay\base\AppUtil;
use allinpay\base\Log;
use allinpay\base\PayException;

class AllinPay
{
    protected $config = array(
        // 接口地址
        'apiurl' => 'https://vsp.allinpay.com/apiweb/unitorder',
        // 接口默认版本
        'version' => 11,
        // 指定不能使用信用卡支付, 不填时不限制no_credit
        'limit_pay' => '',
        // 签名方式(RSA|SM2)
        'signtype' => 'RSA',
        // 支付有效期，单位：分
        'validtime' => '5',
        // RSA私钥
        'private_key' => '',
        // RSA公钥
        'public_key' => '',
        // 日志目录
        'log_path' => '',
    );

    public function __construct($config = [])
    {
        $this->config = array_merge($this->config, $config);
        // 接口地址
        if(empty($this->config["apiurl"]))
        {
            throw new PayException("apiurl 参数错误");
        }
        // 商户号
        if(empty($this->config["cusid"]))
        {
            throw new PayException("cusid 参数错误");
        }
        // 应用ID
        if(empty($this->config["appid"]))
        {
            throw new PayException("appid 参数错误");
        }
    }

    public function request($data)
    {
        $data['cusid'] = $this->config['cusid'];
        $data['appid'] = $this->config['appid'];
        $data['version'] = $this->config['version'];
        $data['randomstr'] = AppUtil::GetNonceStr();
        $data['signtype'] = $this->config['signtype'];
        $data['sign'] = urlencode(AppUtil::Sign($data, $this->config['private_key']));
        $dataStr = AppUtil::ToUrlParams($data);
        Log::Write($this->config['log_path'].'/'.date('Y-m-d').'.log', $data, '请求参数');
        $res = $this->CurlPost($this->config['apiurl'], $dataStr, true);
        return $res;
    }

    public static function CurlPost($url,$params, $json = false){
        $ch = curl_init();
        $this_header = array("content-type: application/x-www-form-urlencoded;charset=UTF-8");
        curl_setopt($ch,CURLOPT_HTTPHEADER,$this_header);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);//如果不加验证,就设false,商户自行处理
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        $output = curl_exec($ch);
        curl_close($ch);
        if($json)
        {
            return json_decode($output,true);
        }
        return  $output;
    }
}