<?php
/**
 * Created by PhpStorm.
 * User: asasi
 * Date: 2018/8/8
 * Time: 10:53
 */

namespace App\Command;


use App\Entity\Video;
use App\Entity\VideoCate;
use App\Entity\VideoSource;
use App\ServiceInterface\VideoCateServiceInterface;
use App\ServiceInterface\VideoServiceInterface;
use App\ServiceInterface\VideoSourceServiceInterface;
use by\component\http\HttpRequest;
use Doctrine\DBAL\DBALException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DailyTaskCommand extends Command
{

    protected $videoCateService;
    protected $videoService;

    /**
     * @var OutputInterface
     */
    protected $output;
    protected $debugFlag;
    protected $logger;

    public function __construct(
        LoggerInterface $logger,
        VideoServiceInterface $videoService, VideoCateServiceInterface $videoCateService, string $name = null)
    {
        parent::__construct($name);
        $this->videoCateService = $videoCateService;
        $this->videoService = $videoService;
        $this->debugFlag = false;
        $this->logger = $logger;
    }

    protected function configure()
    {
        $this->setName("task:daily")
            ->setDescription("okzy command")
            ->setHelp('okzy crawler');

    }

    protected function debug($msg)
    {
        if ($this->debugFlag) {
            $this->output->writeln($msg);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $debug = $input->getOption('verbose');
        if ($debug) {
            $this->debugFlag = true;
        }
        $this->output = $output;
        if (!(date('H') >= 0 && date('H') < 5)) {
            // 0 - 5 点进行执行
            return;
        }
        $allCate = $this->videoCateService->queryAllBy([]);
        foreach ($allCate as $cate) {
            $cateId = $cate['id'];
            $cnt = $this->videoService->count(['cate_id' => $cateId]);
            if (intval($cate['vid_cnt']) != intval($cnt)) {
                $this->videoCateService->updateOne(['id' => $cateId], ['vid_cnt' => $cnt]);
            }
        }


    }

}
