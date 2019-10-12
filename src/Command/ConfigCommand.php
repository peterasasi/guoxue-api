<?php


namespace App\Command;

use App\Entity\Clients;
use App\Entity\Config;
use App\ServiceInterface\ClientsServiceInterface;
use App\ServiceInterface\ConfigServiceInterface;
use by\infrastructure\base\CallResult;
use by\infrastructure\helper\CallResultHelper;
use by\infrastructure\helper\Object2DataArrayHelper;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class ConfigCommand extends Command
{

    protected $configService;
    protected $clientsService;


    public function __construct(ClientsServiceInterface $clientsService, ConfigServiceInterface $configService, string $name = null)
    {
        parent::__construct($name);
        $this->configService = $configService;
        $this->clientsService = $clientsService;
    }

    protected function configure()
    {
        $this
            ->setName('app:config:init')
            ->setDescription('Load data fixtures to your database')
            ->addOption('project_id', null, InputOption::VALUE_REQUIRED, '', '');
    }

    /**
     * @param CallResult $result
     * @return false|string
     * @throws \ReflectionException
     */
    protected function jsonReturn(CallResult $result) {
        return json_encode(Object2DataArrayHelper::getDataArrayFrom($result));
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return CallResult|int|void|null
     * @throws \Doctrine\DBAL\DBALException
     * @throws \ReflectionException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $projectId = $input->getOption('project_id');
        if (empty($projectId)) {
            $output->write($this->jsonReturn(CallResultHelper::fail('empty project_id')));
            return ;
        }

        $client = $this->clientsService->info(['project_id' => $projectId]);

        if (!$client instanceof Clients) {
            $output->write($this->jsonReturn(CallResultHelper::fail('project_id '.$projectId.' not exists')));
            return ;
        }


        $cfg = $this->configService->info(['project_id' => $projectId]);
        if ($cfg instanceof Config) {
            $output->write($this->jsonReturn(CallResultHelper::fail('config had initialized')));
            return;
        }


        $conn = $this->configService->getEntityManager()->getConnection();
        $finder = new Finder();
        $finder->in(__DIR__. '/../../sql/');
        $finder->name('common_config.sql');
        foreach ($finder as $file) {
            $sql = $file->getContents();
            $sql = str_replace('{project_id}', $projectId, $sql);
            $result = $conn->exec($sql);
            $output->write($this->jsonReturn(CallResultHelper::success('Row Effect '.$result.' Rows.')));
            break;
        }


        return;
    }

}
