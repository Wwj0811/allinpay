<?php
namespace allinpay\struct;

class Refunds
{
    /**
     * 商户退款交易单号
     * @var string
     */
    public $reqsn;

    /**
     * 交易金额，单位为分
     */
    public $trxamt;

    /**
     * 原交易的商户单号
     */
    public $oldreqsn;

    /**
     * 原交易的收银宝平台流水
     * oldreqsn和oldtrxid必填其一
     * 建议:商户如果同时拥有oldtrxid和oldreqsn,优先使用oldtrxid
     */
    public $oldtrxid = '';

    /**
     * 支付时间
     * 根据支付时间判断走撤销还是退款
     */
    public $paytime;
}