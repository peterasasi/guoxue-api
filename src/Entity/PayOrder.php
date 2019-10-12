<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="uniq_key", columns={"client_id", "out_order_no"}), @ORM\UniqueConstraint(name="uniq_pay_code", columns={"pay_code"})})
 * @ORM\Entity(repositoryClass="App\Repository\PayOrderRepository")
 */
class PayOrder extends BaseEntity
{

    // 初始化
    const RefundStatusInitial = 0;

    // 全部退款
    const RefundStatusAll = 2;

    // 部分退款
    const RefundStatusPart = 1;




    const PayStatusInitial = 0;

    const PayStatusSuccess = 1;


    /**
     * 支付宝 PC端支付
     */
    const PayTypeOfAliPayPc = 'alipay_pc';

    /**
     * 支付宝 wap端支付
     */
    const PayTypeOfAliPayWap = 'alipay_wap';

    /**
     * 微信支付
     */
    const PayTypeOfWechatPayWx = 'wechat_wx';

    /**
     * 回调状态初始化
     */
    const CallbackStatusInitial = 0;

    /**
     * 回调成功
     */
    const CallbackStatusSuccess = 1;

    /**
     * 回调失败
     */
    const CallbackStatusFailed = -1;

    /**
     * 回调失败一次之后的重试状态
     */
    const CallbackStatusRetry = 2;



    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * 支付编码
     * @ORM\Column(type="string", length=64)
     */
    private $payCode;

    /**
     * 预计支付金额
     * @ORM\Column(type="bigint", options={"default":"0", "comment"="预计支付金额"})
     */
    private $money;

    /**
     * 支付时间
     * @ORM\Column(type="bigint", options={"default":"0", "comment"="支付成功时间"})
     */
    private $payTime;

    /**
     * 支付类型
     * @ORM\Column(type="string", options={"default":"", "comment"="支付通道类型"})
     */
    private $payType;

    /**
     * @ORM\Column(type="bigint")
     */
    private $createTime;

    /**
     * @ORM\Column(type="bigint")
     */
    private $updateTime;

    /**
     * 回调处理时的时间
     * @ORM\Column(type="bigint")
     */
    private $notifyTime;

    /**
     * 回调时的金额
     * @ORM\Column(type="bigint")
     */
    private $notifyMoney;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $tradeNo;

    /**
     * 0 未退款 1 部分退款 2 全额退款
     * @ORM\Column(type="smallint")
     */
    private $refundStatus;

    /**
     * @ORM\Column(type="string", length=512)
     */
    private $refundPayload;

    /**
     * @ORM\Column(type="smallint")
     */
    private $payStatus;

    /**
     * 第三方标识
     * @ORM\Column(type="string", length=64)
     */
    private $clientId;

    /**
     * 第三方交易订单号
     * @ORM\Column(type="string", length=64)
     */
    private $outOrderNo;

    /**
     * @ORM\Column(type="string", length=1024)
     */
    private $payLoad;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $tradeStatus;

    /**
     * @ORM\Column(type="string", length=256)
     */
    private $note;

    /**
     * @ORM\Column(type="string", length=80)
     */
    private $subject;

    /**
     * @ORM\Column(type="integer", options={"default":"0", "comment"="回调状态1成功0初始-1失败2处理中"})
     */
    private $callbackStatus;

    /**
     * @ORM\Column(type="string", length=256, options={"default":"","comment"="回调地址要进行URL编码"})
     */
    private $callback;

    /**
     * @ORM\Column(type="bigint", options={"default":"0", "comment"="回调成功的时间失败不记录"})
     */
    private $callbackNotifyTime;

    /**
     * @ORM\Column(type="string", length=256)
     */
    private $returnUrl;

    /**
     * @ORM\Column(type="integer")
     */
    private $callbackCnt;

    /**
     * PayOrder constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setPayTime(0);
        $this->setCallback('');
        $this->setPayLoad('');
        $this->setClientId('');
        $this->setPayStatus(self::PayStatusInitial);
        $this->setCallbackStatus(self::CallbackStatusInitial);
        $this->setNotifyTime(0);
        $this->setRefundStatus(self::RefundStatusInitial);
        $this->setMoney(0);
        $this->setRefundPayload('');
        $this->setPayType('');
        $this->setPayCode('');
        $this->setOutOrderNo('');
        $this->setTradeStatus('');
        $this->setNote('');
        $this->setNotifyMoney(0);
        $this->setTradeNo('');
        $this->setSubject('');
        $this->setCallbackNotifyTime(0);
        $this->setCallbackCnt(0);
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPayCode(): ?string
    {
        return $this->payCode;
    }

    public function setPayCode(string $payCode): self
    {
        $this->payCode = $payCode;

        return $this;
    }

    public function getMoney(): ?int
    {
        return $this->money;
    }

    public function setMoney(int $money): self
    {
        $this->money = $money;

        return $this;
    }

    public function getPayTime(): ?int
    {
        return $this->payTime;
    }

    public function setPayTime(int $payTime): self
    {
        $this->payTime = $payTime;

        return $this;
    }

    public function getPayType(): ?string
    {
        return $this->payType;
    }

    public function setPayType(string $payType): self
    {
        $this->payType = $payType;

        return $this;
    }

    public function getCreateTime(): ?int
    {
        return $this->createTime;
    }

    public function setCreateTime(int $createTime): self
    {
        $this->createTime = $createTime;

        return $this;
    }

    public function getUpdateTime(): ?int
    {
        return $this->updateTime;
    }

    public function setUpdateTime(int $updateTime): self
    {
        $this->updateTime = $updateTime;

        return $this;
    }

    public function getNotifyTime(): ?int
    {
        return $this->notifyTime;
    }

    public function setNotifyTime(int $notifyTime): self
    {
        $this->notifyTime = $notifyTime;

        return $this;
    }

    public function getNotifyMoney(): ?int
    {
        return $this->notifyMoney;
    }

    public function setNotifyMoney(int $notifyMoney): self
    {
        $this->notifyMoney = $notifyMoney;

        return $this;
    }

    public function getTradeNo(): ?string
    {
        return $this->tradeNo;
    }

    public function setTradeNo(string $tradeNo): self
    {
        $this->tradeNo = $tradeNo;

        return $this;
    }

    public function getRefundStatus(): ?int
    {
        return $this->refundStatus;
    }

    public function setRefundStatus(int $refundStatus): self
    {
        $this->refundStatus = $refundStatus;

        return $this;
    }

    public function getRefundPayload(): ?string
    {
        return $this->refundPayload;
    }

    public function setRefundPayload(string $refundPayload): self
    {
        $this->refundPayload = $refundPayload;

        return $this;
    }

    public function getPayStatus(): ?int
    {
        return $this->payStatus;
    }

    public function setPayStatus(int $payStatus): self
    {
        $this->payStatus = $payStatus;

        return $this;
    }

    public function getCallback(): ?string
    {
        return $this->callback;
    }

    public function setCallback(string $callback): self
    {
        $this->callback = $callback;

        return $this;
    }

    public function getClientId(): ?string
    {
        return $this->clientId;
    }

    public function setClientId(string $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function getOutOrderNo(): ?string
    {
        return $this->outOrderNo;
    }

    public function setOutOrderNo(string $outOrderNo): self
    {
        $this->outOrderNo = $outOrderNo;

        return $this;
    }

    public function getPayLoad(): ?string
    {
        return $this->payLoad;
    }

    public function setPayLoad(string $payLoad): self
    {
        $this->payLoad = $payLoad;

        return $this;
    }

    public function getCallbackStatus(): ?int
    {
        return $this->callbackStatus;
    }

    public function setCallbackStatus(int $callbackStatus): self
    {
        $this->callbackStatus = $callbackStatus;

        return $this;
    }

    public function getTradeStatus(): ?string
    {
        return $this->tradeStatus;
    }

    public function setTradeStatus(string $tradeStatus): self
    {
        $this->tradeStatus = $tradeStatus;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getCallbackNotifyTime(): ?int
    {
        return $this->callbackNotifyTime;
    }

    public function setCallbackNotifyTime(int $callbackNotifyTime): self
    {
        $this->callbackNotifyTime = $callbackNotifyTime;

        return $this;
    }

    public function getReturnUrl(): ?string
    {
        return $this->returnUrl;
    }

    public function setReturnUrl(string $returnUrl): self
    {
        $this->returnUrl = $returnUrl;

        return $this;
    }

    public function getCallbackCnt(): ?int
    {
        return $this->callbackCnt;
    }

    public function setCallbackCnt(int $callbackCnt): self
    {
        $this->callbackCnt = $callbackCnt;

        return $this;
    }

}
