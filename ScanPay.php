<?php

namespace allinpay;


use allinpay\base\AppUtil;
use allinpay\base\Log;
use allinpay\base\PayException;

class ScanPay extends AllinPay
{
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->config['apiurl'] .= 'unitorder/scanqrpay';
    }

    /**
     * 扫码支付接口
     * @param struct\ScanPay $params
     * @throws PayException
     */
    public function WechatScanPay($params)
    {
        try{
            // 金额
            if(empty($params->trxamt) || $params->trxamt <= 0) {
                throw new PayException("交易金额有误");
            }
            // 订单号
            if(empty($params->reqsn)) {
                throw new PayException("订单号有误");
            }else{
                if(strlen($params->reqsn) > 32) {
                    throw new PayException("订单号长度有误");
                }
            }
            // 支付授权码
            if(empty($params->reqsn)) {
                throw new PayException("支付授权码有误");
            }else{
                if(strlen($params->reqsn) > 32) {
                    throw new PayException("支付授权码长度有误");
                }
            }

            $data = [
                'trxamt' => $params->trxamt,
                'reqsn' => $params->reqsn,
                'body' => $params->body,
                'remark' => $params->remark,
                'authcode' => $params->authcode,
                'limit_pay' => $this->config['limit_pay'],
                'notify_url' => $params->notify_url,
            ];
            $res = $this->request($data);

            // 下单接口请求失败
            if($res["retcode"] == "FAIL") {
                // 下单失败记录
                throw new PayException("下单失败：".$res["retmsg"]);
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