<?php


namespace App\MessageHandler;


use App\Entity\Config;
use App\Message\UserRegisterMsg;
use App\ServiceInterface\ConfigServiceInterface;
use by\component\encrypt\des\Des;
use Dbh\SfCoreBundle\Common\ByEnv;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class UserRegisterMsgHandler implements MessageHandlerInterface
{

    protected $mailer;
    protected $container;
    protected $kernel;
    protected $configService;
    protected $template;
    protected $logger;

    public function __construct(
        LoggerInterface $logger,
        ConfigServiceInterface $configService,
        KernelInterface $kernel, Environment $twig,
        ContainerInterface $container, \Swift_Mailer $mailer)
    {
        $this->logger = $logger;
        $this->template = $twig;
        $this->kernel = $kernel;
        $this->mailer = $mailer;
        $this->container = $container;
        $this->configService = $configService;
    }


    public function __invoke(UserRegisterMsg $userRegisterMsg)
    {
        try {
            $activeKey = json_encode([
                'u' => $userRegisterMsg->getUid(),
                't' => time(),
                'p' => $userRegisterMsg->getProjectId()
            ]);
            $key = substr(ByEnv::get('APP_SECRET'), 0, 8);
            $activeKey = base64_encode(Des::encode($activeKey, $key));

            $activeUrl = $this->container->get('router')->generate('user_active', ['activeKey' => $activeKey], UrlGeneratorInterface::ABSOLUTE_PATH);
            $activeUrl = rtrim(ByEnv::get('ROOT_DOMAIN'), '/') . '/' . ltrim($activeUrl, '/');

            $brand = $this->getBrand($userRegisterMsg->getProjectId());

            $tpl = $this->template->render("emails/registration.html.twig", [
                'brand' => $brand,
                'activeUrl' => $activeUrl
            ]);

            $message = (new \Swift_Message('注册激活邮件'))
                ->setFrom('', 'DBH')
                ->setTo(($userRegisterMsg->getEmail()))
                ->setBody(
                    $tpl,
                    'text/html'
                );

            $this->mailer->send($message, $failed);
            if (count($failed) > 0) {
                $this->logger->error('[EmailCodeMsgHandler]Failed'.json_encode($failed));
            }
        } catch (\Exception $exception) {
            $this->logger->error('[UserRegisterMsg]异常'.$exception->getTraceAsString());
            throw $exception;
        }
    }

    protected function getBrand($projectId)
    {
        $config = $this->configService->info(['name' => 'SYS_BRAND', 'project_id' => $projectId]);
        if (!$config instanceof Config) return 'BRAND';
        return $config->getValue();
    }
}
