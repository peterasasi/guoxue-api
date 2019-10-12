<?php


namespace App\MessageHandler;


use App\Entity\Config;
use App\Message\EmailCodeMsg;
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

class EmailCodeMsgHandler implements MessageHandlerInterface
{

    protected $mailer;
    protected $twig;
    protected $kernel;
    protected $configService;
    protected $logger;

    public function __construct(
        LoggerInterface $logger,
        ConfigServiceInterface $configService,
        KernelInterface $kernel, Environment $twig, \Swift_Mailer $mailer)
    {
        $this->logger = $logger;
        $this->kernel = $kernel;
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->configService = $configService;
    }


    public function __invoke(EmailCodeMsg $emailCodeMsg)
    {
        try {

            $brand = $this->getBrand($emailCodeMsg->getProjectId());

            $tpl = $this->twig->render("emails/verification_code.html.twig", [
                'brand' => $brand,
                'code' => $emailCodeMsg->getCode()
            ]);

            $message = (new \Swift_Message($brand . '验证码邮件'))
                ->setFrom('', $brand)
                ->setTo(($emailCodeMsg->getToEmail()))
                ->setBody(
                    $tpl,
                    'text/html'
                );
            $this->mailer->send($message, $failed);
            if (count($failed) > 0) {
                $this->logger->error('[EmailCodeMsgHandler]Failed'.json_encode($failed));
            }
        } catch (\Exception $exception) {
            $this->logger->error('[EmailCodeMsgHandler]异常' . $exception->getTraceAsString());
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
