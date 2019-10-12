<?php
/**
 * Created by PhpStorm.
 * User: asasi
 * Date: 2018/8/8
 * Time: 10:53
 */

namespace App\Command;


use by\infrastructure\helper\CallResultHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MockCommand extends Command
{

    protected function configure()
    {
        $this->setName("mock:fee")
            ->setDescription("mock fee command")
            ->setHelp('模拟手续费');
//        $this->addArgument('username', InputArgument::REQUIRED, 'The username of the user.');
//        $this->addArgument('password', InputArgument::REQUIRED, 'The password of the user.');

        $this->addOption('count', 'c', InputArgument::OPTIONAL, '', 2);
        $this->addOption('money', 'm', InputArgument::OPTIONAL, '', 500);
//        $this->addOption('type', InputArgument::OPTIONAL, '');
//        $this->addOption('code', InputArgument::OPTIONAL, '');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $count = $input->getOption('count');
        $money = $input->getOption('money');
        $payRate = 0.006;//千分之6
        $fixed = 0.6;

        $total = $this->getInitialMoney($money, $count, $payRate, $fixed);
        $total = $this->format($total);
        $output->writeln('*****支付手续费' . $payRate . ',提现手续费每笔' . $fixed . '元*****');
        $output->writeln('*****每次还款' . $money . '元*****');
        $output->writeln('*****预存' . $total . '元*****');
//        $total
        $note = '从储蓄卡卡转移' . $total . '元手续费到第三方，支付手续费=' . $this->format($total * $payRate);
        $output->writeln('[支出手续费]' . $note);
        $totalFee = $this->format($total * $payRate);
        // 信用卡
        while ($count--) {
            $cnt = 1;
            $curMoney = 0;
            while (($cnt -1) * 1000 <= $money) {
                if ($cnt * 1000 > $money) {
                    $curMoney = $cnt * 1000 - $money;
                } else {
                    $curMoney = 1000;
                }
                $totalFee += $this->format($curMoney * $payRate);
                $note = '从卡转' . $curMoney . '元到第三方，扣除支付手续费=' . $this->format($curMoney * $payRate) . ';实到' . ($curMoney - $this->format($curMoney * $payRate));
                $output->writeln('[支付]' . $note);
                $cnt++;
            }
            $cnt = 1;
            $curMoney = 0;
            while (($cnt -1) * 1000 <= $money) {
                $totalFee += $fixed;
                if ($cnt * 1000 > $money) {
                    $curMoney = $cnt * 1000 - $money;
                } else {
                    $curMoney = 1000;
                }
                $note = '第三方转移' . ($curMoney + $fixed) . '元到卡，扣除提现手续费=' . $fixed . ';实到' . $curMoney;
                $output->writeln('[提现]' . $note);
                $cnt++;
            }
        }
        $output->writeln('[余额]' . ($total - $totalFee));
        $output->writeln('[消耗手续费]' . $totalFee);

    }

    protected function startFromtThird()
    {

//        $total = $this->getInitialMoney($money, $count, $payRate, $fixed);
//        $total = $this->format($total);
//        $output->writeln('*****支付手续费'.$payRate.',提现手续费每笔'.$fixed.'元*****');
//        $output->writeln('*****每次还款'.$money.'元*****');
//        $output->writeln('*****预存'.$total.'元*****');
////        $total
//        $note = '从储蓄卡转移' . $total . '元到第三方，支付手续费=' . $this->format($total * $payRate);
//        $output->writeln('[支出]' . $note);
//        $totalFee += $this->format($total * $payRate);
//        // 信用卡
//        while ($count--) {
//
//            $payMoney = $money + $fixed;
//            $note = '第三方转移' . $payMoney . '元到卡，扣除提现手续费=' . $fixed . ';实到' . ($payMoney - $fixed);
//            $output->writeln('[提现]' . $note);
//
//            $totalFee += $fixed;
//            $payMoney = $money;
//            $note = '从卡转' . $payMoney . '元到第三方，扣除支付手续费=' . $this->format($payMoney * $payRate) . ';实到' . ($payMoney - $this->format($payMoney * $payRate));
//            $output->writeln('[支付]' . $note);
//            $totalFee += $this->format($payMoney * $payRate);
//        }
//        $totalFee += $fixed;
//        $output->writeln('[余额]'.($total - $totalFee));
//        $output->writeln('[消耗手续费]'.$totalFee);
    }

    protected function getInitialMoney($money, $count, $payRate, $fixed)
    {
        // 1000 的倍数
        if ($money < 1000) {
            $part = 1;
        } else {
            $part = ceil($money / 1000);
        }

        $totalFee = $count * $part * $fixed + $count * $money * $payRate;

        return 0.5 + number_format(($totalFee) / (1 - $payRate), "2", ".", "");

//        $totalFee = 0;
//        $totalFee += $count * ($fixed + $money * $payRate);
//
//        $initMoney = $totalFee + $fixed;
//        $initMoney = $initMoney / (1 - $payRate);
//
//        return $initMoney;
    }

    protected function format($num)
    {
        return number_format($num, 2, ".", "");
    }
}
