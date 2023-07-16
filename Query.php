<?php

namespace allinpay;

use allinpay\base\AppUtil;
use allinpay\base\Log;
use allinpay\base\PayException;

class Query extends AllinPay
{
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->config['apiurl'] .= 'tranx/query';
        $this->config['log_path'] .= 'query';
    }

    /**
     * 交易查询，该接口提供交易查询，商户可以通过查询接口主动查询订单状态，完成下一步的业务逻辑
     * 当商户后台、网络、服务器等出现异常，商户系统最终未接收到支付通知；
     * 调用支付接口后，返回系统错误或未知交易状态情况；
     * 调用统一被扫接口后，返回交易状态码trxstatus为2000时；
     * 调用关单或撤销接口API之前，需确认支付状态；
     * @param struct\Query $params
     * @throws PayException
     */
    public function Query($params)
    {
        try {
            // 订单号
            if(empty($params->reqsn)) {
                throw new PayException("订单号有误");
            }else{
                if(strlen($params->reqsn) > 32) {
                    throw new PayException("订单号长度有误");
                }
            }

            $data = [
                'reqsn' => $params->reqsn,
            ];
            $res = $this->request($data);

            // 接口请求失败
            if($res["retcode"] == "FAIL") {
                throw new PayException("查询失败：".$res["retmsg"]);
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