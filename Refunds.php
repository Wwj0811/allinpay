<?php

namespace allinpay;

use allinpay\base\AppUtil;
use allinpay\base\Log;
use allinpay\base\PayException;

class Refunds extends AllinPay
{
    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    /**
     * 根据支付时间判断走撤销还是退款
     * @param object $params
     * @throws PayException
     */
    public function Refunds($params)
    {
        try {
            // 金额
            if(empty($params->trxamt) || $params->trxamt <= 0) {
                throw new PayException("退款金额有误");
            }
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
            if(substr($params->paytime,0, 10) == date('Y-m-d')){
                return (new Cancel($this->config))->Cancel($params);
            }else{
                return (new Refund($this->config))->Refund($params);
            }
        }catch(\Exception $e){
            Log::Write($this->config['log_path'].'/'.date('Y-m-d').'.log', $e->getMessage(), '异常');
            throw new PayException($e->getMessage());
        }
    }
}