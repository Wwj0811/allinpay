<?php

namespace allinpay\struct;

class ScanPay
{
    /**
     * 交易金额，单位分
     */
    public $trxamt;

    /**
     * 商户交易单号
     * @var string
     */
    public $reqsn;

    /**
     * 订单标题
     * @var string
     */
    public $body = '';

    /**
     * 备注
     * @var string
     */
    public $remark = '';

    /**
     * 支付授权码
     * @var string
     */
    public $authcode = '';

    /**
     * 交易结果通知地址
     * @var string
     */
    public $notify_url = '';

    /**
     * 微信子appid
     * @var string
     */
    public $sub_appid = '';
}
