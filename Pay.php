<?php

namespace allinpay;


use allinpay\base\AppUtil;
use allinpay\base\Log;
use allinpay\base\PayException;

class Pay extends AllinPay
{
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->config['apiurl'] .= 'apiweb/unitorder/pay';
        $this->config['log_path'] .= 'pay';
    }

    /**
     * 微信jsapi支付
     * @param struct\WechatJsapiPay $params
     * @throws PayException
     */
    public function WechatJsapiPay($params)
    {
        try {
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

            $data = [
                'trxamt' => $params->trxamt,
                'reqsn' => $params->reqsn,
                'paytype' => $params->paytype,
                'body' => $params->body,
                'remark' => $params->remark,
                'acct' => $params->acct,
                'limit_pay' => $this->config['limit_pay'],
                'notify_url' => $params->notify_url,
                'front_url' => $params->front_url,
                'validtime' => $params->validtime,
                'sub_appid' => $params->sub_appid,
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
        }catch (\Exception $e) {
            Log::Write($this->config['log_path'].'/'.date('Y-m-d').'.log', $e->getMessage(), '异常');
            throw new PayException($e->getMessage());
        }
    }
}