<?php
namespace allinpay\struct;

class WechatJsapiPay extends Pay
{
    /**
     * 交易方式
     * @var string
     */
    public $paytype = 'W02';

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

    /**
     * 用户的微信openid
     * @var string
     */
    public $acct;
}