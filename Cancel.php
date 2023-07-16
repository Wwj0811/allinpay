<?php

namespace allinpay;

use allinpay\base\AppUtil;
use allinpay\base\Log;
use allinpay\base\PayException;

class Cancel extends AllinPay
{
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->config['apiurl'] .= 'tranx/cancel';
    }

    /**
     * 交易撤销，只能撤销当天的交易，全额退款，实时返回退款结果
     * @param struct\Cancel $params
     * @throws PayException
     */
    public function Cancel($params)
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

            if(empty($params->oldreqsn)) {
                throw new PayException("原始订单号有误");
            }else{
                if(strlen($params->oldreqsn) > 32) {
                    throw new PayException("原始订单号长度有误");
                }
            }

            $data = [
                'trxamt' => $params->trxamt,
                'reqsn' => $params->reqsn,
                'oldreqsn' => $params->oldreqsn,
            ];
            $res = $this->request($data);

            // 下单接口请求失败
            if($res["retcode"] == "FAIL") {
                // 下单失败记录
                throw new PayException("撤销失败：".$res["retmsg"]);
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