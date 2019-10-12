<?php
/**
 * Created by PhpStorm.
 * User: asasi
 * Date: 2018/8/15
 * Time: 15:15
 */

namespace App\Service;


use App\Common\GuoxueGlobalConfig;
use App\Entity\Config;
use App\Repository\ConfigRepository;
use App\ServiceInterface\ConfigServiceInterface;
use by\component\config\ConfigParser;
use by\infrastructure\base\CallResult;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\BaseService;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpKernel\KernelInterface;


class ConfigService extends BaseService implements ConfigServiceInterface
{
    /**
     * @var ConfigRepository
     */
    protected $repo;
    protected $kernel;

    public function __construct(KernelInterface $kernel, ConfigRepository $repository)
    {
        $this->repo = $repository;
        $this->kernel = $kernel;
    }

    /**
     * @param $projectId
     * @return \by\infrastructure\base\CallResult|mixed|string
     * @throws \Exception
     */
    public function initByProjectId($projectId)
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'app:config:init',
            '--project_id' => $projectId
        ]);

        $output = new BufferedOutput();
        $application->run($input, $output);

        $content = $output->fetch();

        $content = json_decode($content, JSON_OBJECT_AS_ARRAY);
        if (is_array($content)) {
            return new CallResult($content['data'], $content['msg'], $content['code']);
        }

        return CallResultHelper::fail(json_encode($content));
    }

    public function getGxConfig($projectId) {

    }
}
