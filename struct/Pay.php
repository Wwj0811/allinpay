<?php
namespace allinpay\struct;

class Pay
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
     * 交易方式
     * W01	微信扫码支付
     * W02	微信JS支付
     * W06	微信小程序支付
     * A01	支付宝扫码支付
     * A02	支付宝JS支付
     * A03	支付宝APP支付
     * U01	银联扫码支付(CSB)
     * U02	银联JS支付
     * S01	数币扫码支付
     * 03	数字货币H5
     * @var string
     */
    public $paytype;

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
     * 订单有效时间，以分为单位，最大1440分钟
     * @var int
     */
    public $validtime = 5;
}