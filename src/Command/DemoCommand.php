<?php
/**
 * Created by PhpStorm.
 * User: asasi
 * Date: 2018/8/8
 * Time: 10:53
 */

namespace App\Command;


use App\Helper\DesHelper;
use App\Message\PaySuccessMsg;
use App\ServiceInterface\PayOrderServiceInterface;
use App\ServiceInterface\UserWalletServiceInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Process\Process;

class DemoCommand extends Command
{

    protected $mailer;
    protected $walletService;
    protected $eventDispatcher;
    protected $messageBus;
    protected $payOrderService;

    public function __construct(
        PayOrderServiceInterface $payOrderService,
        MessageBusInterface $messageBus,
        EventDispatcherInterface $eventDispatcher,
        UserWalletServiceInterface $walletService,
        \Swift_Mailer $swift_Mailer, string $name = null)
    {
        parent::__construct($name);
        $this->mailer = $swift_Mailer;
        $this->walletService = $walletService;
        $this->eventDispatcher = $eventDispatcher;
        $this->messageBus = $messageBus;
        $this->payOrderService = $payOrderService;
    }

    protected function configure()
    {
        $this->setName("demo:index")
            ->setDescription("demo command")
            ->setHelp('This command just a demo for you');
//        $this->addArgument('username', InputArgument::REQUIRED, 'The username of the user.');
//        $this->addArgument('password', InputArgument::REQUIRED, 'The password of the user.');

        $this->addOption('flag', 'f', InputArgument::OPTIONAL);
//        $this->addOption('type', InputArgument::OPTIONAL, '');
//        $this->addOption('code', InputArgument::OPTIONAL, '');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $payOrder = $this->payOrderService->info(['out_order_no' => '2339', 'client_id' => 'by04esfH0fdc6Y']);
        $ret = $this->payOrderService->notify($payOrder);
        var_dump($ret);

//        $msg = new PaySuccessMsg();
//        $msg->setTotalAmount(100);
//        $msg->setOutOrderNo('2336');
//        $msg->setSubject('');
//
//        $msg->setPayTime(time());
//        $msg->setPayCode('73258015638613912C64AC8A310357');
//        $msg->setNote('');
//
//        $this->messageBus->dispatch($msg);

//        $this->eventDispatcher->dispatch(new )
//        $des = "ZhgGzhe4EVqtvL2uxx0Tk9w+tOQwtahlgniH9zAdBUY=";
//        var_dump(DesHelper::decode($des));
//        $message = (new \Swift_Message('注册激活邮件'))
//            ->setBody(
//                'test',
//                'text/html'
//            );
//
//        $ret = $this->mailer->send($message);
//        var_dump($ret);
//        var_dump($this->mailer->getTransport()->ping());

//        $output->writeln('Username: '.$input->getOption('flag'));
//        $process = new Process(['/usr/bin/which', 'ssh']);
//        $process->start();
//
//        foreach ($process as $type => $data) {
//            if ($process::OUT === $type) {
//                echo "\nStdout: ".$data;
//            } else { // $process::ERR === $type
//                echo "\nStderr: ".$data;
//            }
//        }

//        $process = (new Process([]))->setTty();
//        var_dump(Process::isTtySupported());
//        $process->wait();
//        $output->writeln('Username: '.$input->getOption('type'));
//        $output->writeln('Username: '.$input->getOption('code'));
//        $output->writeln('Username: '.$input->getArgument('username'));
//        $output->writeln('Password: '.$input->getArgument('password'));
    }
}
