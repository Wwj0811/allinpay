<?php

namespace allinpay;


use allinpay\base\AppUtil;
use allinpay\base\Log;
use allinpay\base\PayException;

class Addterm extends AllinPay
{
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->config['apiurl'] .= 'cusapi/merchantapi/addterm';
        $this->config['log_path'] .= 'scanpay';
    }

    /**
     * 终端信息采集接口
     * @param struct\Addterm $params
     * @throws PayException
     */
    public function Add($params)
    {
        try{
            // 终端号
            if(empty($params->termno) || strlen($params->termno) != 8) {
                throw new PayException("终端号有误");
            }else{
                if(strlen($params->termno) != 8) {
                    throw new PayException("终端号长度有误");
                }
            }
            // 设备类型
            if(empty($params->devicetype)) {
                throw new PayException("设备类型有误");
            }
            // 操作类型
            if(empty($params->operation)) {
                throw new PayException("操作类型有误");
            }
            // 终端状态
            if(empty($params->termstate)) {
                throw new PayException("终端状态有误");
            }
            // 终端地址
            if(empty($params->termaddress)) {
                throw new PayException("终端地址有误");
            }

            $data = [
                'termno' => $params->termno,
                'devicetype' => $params->devicetype,
                'termsn' => $params->termsn,
                'operation' => $params->operation,
                'termstate' => $params->termstate,
                'termaddress' => $params->termaddress,
            ];
            $res = $this->request($data);

            if($res["retcode"] == "FAIL") {
                throw new PayException("终端信息采集失败：".$res["retmsg"]);
            }
            // 验证签名
            if(!AppUtil::validSign($res, $this->config["public_key"])){
                throw new PayException("签名验证错误");
            }
            return $res;
        }catch (\Exception $e){
            Log::Write($this->config['log_path'].'/'.date('Y-m-d').'.log', $e->getMessage(), '异常');
            throw new PayException($e->getMessage());
        }
    }
}