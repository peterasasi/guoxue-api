<?php
/**
 * Created by PhpStorm.
 * User: asasi
 * Date: 2018/8/8
 * Time: 10:53
 */

namespace App\Command;


use App\Entity\CfPayNotify;
use App\Entity\CfProject;
use App\ServiceInterface\CfOrderServiceInterface;
use App\ServiceInterface\CfPayNotifyServiceInterface;
use App\ServiceInterface\CfPayOrderServiceInterface;
use App\ServiceInterface\CfProjectServiceInterface;
use by\component\encrypt\rsa\Rsa;
use by\component\paging\vo\PagingParams;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class RsaGeneratorCommand
 * php bin/console rsa:make
 * @package App\Command
 */
class RsaGeneratorCommand extends Command
{
    private $logger;

    public function __construct(LoggerInterface $logger,?string $name = null)
    {
        parent::__construct($name);
        $this->logger = $logger;
    }

    protected function configure()
    {
        $this->setName("rsa:make")
            ->setDescription("rsa 2048 ")
            ->setHelp('rsa 2048 ');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        list($pubKey, $privKey) = Rsa::generateRsaKeys('sha256', '2048');
        $output->writeln($pubKey);
        $output->writeln($privKey);
        $file = new File('./pubic.txt');
        $openFile = $file->openFile('w+');
        $openFile->fwrite($pubKey);
        $file = new File('./private.txt');
        $file->openFile('w+')->fwrite($privKey);
    }

}
