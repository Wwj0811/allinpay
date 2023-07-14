<?php

namespace allinpay;

use allinpay\base\AppUtil;
use allinpay\base\PayException;

class Refund extends AllinPay
{
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->config['apiurl'] .= 'tranx/refund';
    }

    /**
     * 交易退款，支持部分金额退款，隔天交易退款。（建议在交易完成后间隔几分钟（最短5分钟）再调用退款接口，避免出现订单状态同步不及时导致退款失败。）
     * @param struct\Refund $params
     * @throws PayException
     */
    public function Refund($params)
    {
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

        $data = [
            'trxamt' => $params->trxamt,
            'reqsn' => $params->reqsn,
            'oldreqsn' => $params->oldreqsn,
            'remark' => $params->remark
        ];
        $res = $this->request($data);

        // 接口请求失败
        if($res["retcode"] == "FAIL") {
            throw new PayException("退款失败：".$res["retmsg"]);
        }
        // 验证签名
        if(!AppUtil::validSign($res, $this->config["public_key"])){
            throw new PayException("签名验证错误");
        }
        return $res;
    }
}