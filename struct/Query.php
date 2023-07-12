<?php
namespace allinpay\struct;

class Query
{
    /**
     * 商户退款交易单号
     * @var string
     */
    public $reqsn;

    /**
     * 支付的收银宝平台流水
     * reqsn和trxid必填其一
     * 建议:商户如果同时拥有trxid和reqsn,优先使用trxid
     */
    public $trxid;
}