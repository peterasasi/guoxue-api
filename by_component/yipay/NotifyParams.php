<?php


namespace by\component\yipay;


class NotifyParams
{
    protected $orderNumber;
    protected $money;//支付的价格(单位：元)
    protected $token;//签名【md5(订单状态+商户号+商户订单号+支付金额+商户秘钥)】

    public function __construct($data)
    {
        if (is_array($data)) {
            array_key_exists('money', $data) && $this->setMoney($data['money']);
            array_key_exists('token', $data) && $this->setToken($data['token']);
            array_key_exists('orderNumber', $data) && $this->setOrderNumber($data['orderNumber']);
        }
    }

    /**
     * @return mixed
     */
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    /**
     * @param mixed $orderNumber
     */
    public function setOrderNumber($orderNumber): void
    {
        $this->orderNumber = $orderNumber;
    }

    /**
     * @return mixed
     */
    public function getMoney()
    {
        return $this->money;
    }

    /**
     * @param mixed $money
     */
    public function setMoney($money): void
    {
        $this->money = $money;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token): void
    {
        $this->token = $token;
    }

    public function verifyToken($key, $code) {
        $data = [
            'code' => $code,
            'orderNumber' => $this->getOrderNumber(),
            'money' => $this->getMoney(),
            'key' => $key
        ];
        return md5(json_encode($data)) === $this->getToken();
    }
}
