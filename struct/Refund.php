<?php
namespace allinpay\struct;

class Refund
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
    public $oldtrxid;

    /**
     * 备注
     */
    public $remark;
}