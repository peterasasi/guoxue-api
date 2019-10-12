<?php


namespace App\Message;


class PaySuccessMsg
{
//$payCode = $data->get('out_trade_no', '');
//$tradeNo = $data->get('trade_no', '');
//$sellerId = $data->get('seller_id', '');
//$appId = $data->get('app_id', '');
//$totalAmount = $data->get('total_amount', 0);
//$notifyTime = $data->get('gmt_payment', 0);
    protected $outOrderNo;
    protected $subject;
    protected $totalAmount;
    protected $payTime;
    protected $payCode;
    protected $note;

    /**
     * @return mixed
     */
    public function getOutOrderNo()
    {
        return $this->outOrderNo;
    }

    /**
     * @param mixed $outOrderNo
     */
    public function setOutOrderNo($outOrderNo): void
    {
        $this->outOrderNo = $outOrderNo;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return mixed
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    /**
     * @param mixed $totalAmount
     */
    public function setTotalAmount($totalAmount): void
    {
        $this->totalAmount = $totalAmount;
    }

    /**
     * @return mixed
     */
    public function getPayTime()
    {
        return $this->payTime;
    }

    /**
     * @param mixed $payTime
     */
    public function setPayTime($payTime): void
    {
        $this->payTime = $payTime;
    }

    /**
     * @return mixed
     */
    public function getPayCode()
    {
        return $this->payCode;
    }

    /**
     * @param mixed $payCode
     */
    public function setPayCode($payCode): void
    {
        $this->payCode = $payCode;
    }

    /**
     * @return mixed
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param mixed $note
     */
    public function setNote($note): void
    {
        $this->note = $note;
    }

}
