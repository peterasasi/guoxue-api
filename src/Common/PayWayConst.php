<?php


namespace App\Common;


class PayWayConst
{
    /**
     * 未知
     */
    const PW000 = 'unknown';
    /**
     * fake 假支付 - 模拟支付
     */
    const PW_FAKE = 'fake';

    /**
     * 支付通道 1001
     */
    const PW001 = 'pw1001';

    /**
     * 支付通道 1002 - xft
     */
    const PW002 = 'pw1002';

    /**
     * 支付通道 1003- pay841
     */
    const PW841 = 'pw1841';

    /**
     * 支付通道 1004 - yipay
     */
    const PWYIPAY = 'yipay';

    private $pw;

    public function __construct($pw)
    {
        $this->pw = $pw;
    }

    public function __toString()
    {
        switch ($this->pw) {
            case self::PWYIPAY:
                return '易支付';
            case self::PW002:
                return '星富通';
            case self::PW841:
                return 'PAY841';
            case self::PW_FAKE:
                return '模拟测试通道';
            case self::PW000:
            default:
                return '未知通道';
        }
    }
}
