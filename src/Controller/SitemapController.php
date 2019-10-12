<?php


namespace App\Controller;

use App\Entity\Album;
use App\ServiceInterface\AlbumServiceInterface;
use by\component\paging\vo\PagingParams;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SitemapController extends AbstractController
{

    const SITE = [
    ];

    /**
     * @var AlbumServiceInterface
     */
    protected $albumService;

    protected $sitemapCacheDir;

    public function __construct(AlbumServiceInterface $albumService, ContainerInterface $container)
    {
        $this->albumService = $albumService;
        $this->sitemapCacheDir = $container->getParameter('public_dir') . $container->getParameter('sitemap_dir');
    }

    /**
     * @Route("/{site}/sitemap.xml", name="sitemap_album_index", methods={"GET"})
     * @param Request $request
     * @param $site
     * @return Response
     */
    public function albumSitemapIndex(Request $request, $site)
    {
        if (!array_key_exists($site, self::SITE)) {
            return new Response('不支持的站点');
        }
        $sitemapDir = $this->sitemapCacheDir . $site;
        if (!is_dir($this->sitemapCacheDir)) {
            return new Response('请创建站点文件夹');
        }
        $fileName = $sitemapDir . '/sitemap.xml';
        if (file_exists($fileName)) {
            $time = filemtime($fileName);
            if (time() - $time < 8 * 3600) {
                // 8小时以内
                $response = new Response();
                $response->headers->set('X-Cache-MTime', date(DATE_ATOM, $time));
                $response->headers->set('Content-Type', 'text/xml');
                $response->setContent(file_get_contents($fileName));
                return $response;
            }
        }
        $finder = new Finder();
        $data = [];
        $currentMonthUrl = $request->getSchemeAndHttpHost() . '/' . $site . '/' . date("Ym") . '.xml';
        $needAddCurrent = true;
        foreach ($finder->files()->in($sitemapDir) as $file) {
            if ($file instanceof \SplFileInfo) {
                if ($file->getExtension() === 'xml' && $file->getFilename() != 'sitemap.xml' && strlen($file->getFilename()) === 10) {
                    $url = $request->getSchemeAndHttpHost() . '/' . $site . '/' . $file->getFilename();
                    if ($url == $currentMonthUrl) {
                        $needAddCurrent = false;
                    }
                    array_push($data, [
                        'url' => $url,
                        'datetime' => date(DATE_ATOM, $file->getMTime())
                    ]);
                }
            }
        }

        // 避免多次添加当月的xml
        if ($needAddCurrent) {
            array_push($data, [
                'url' => $currentMonthUrl,
                'datetime' => date(DATE_ATOM),
                'changefreq' => 'daily',
                'priority' => 1
            ]);
        }

        usort($data, function ($prev, $next) {
            if ($prev['url'] == $next['url']) return 0;
            if ($prev['url'] > $next['url']) {
                return -1;
            } else {
                return  1;
            }
        });

        $response = $this->getResponse($data, "sitemap");


        @file_put_contents($fileName, $response->getContent());

        return $response;
    }

    /**
     * @Route("/{site}/{ym<\d+>}.{format}", name="sitemap_album_monthly", methods={"GET"})
     * @param $site
     * @param $ym
     * @param $format
     * @return Response
     */
    public function albumMonthly($site, $ym, $format)
    {
        if (!array_key_exists($site, self::SITE)) {
            return new Response('不支持的站点');
        }
        $cache = false;
        if (intval($ym) < intval(date('Ym'))) {
            $cache = true;
        }

        $sitemapDir = $this->sitemapCacheDir . $site;
        if (!is_dir($this->sitemapCacheDir)) {
            return new Response('请创建站点文件夹');
        }
        $fileName = $sitemapDir . '/' . $ym . '.' . $format;
        if (file_exists($fileName)) {
            $response = new Response();
            $response->headers->set('Content-Type', 'text/' . $format);
            $response->setContent(file_get_contents($fileName));
            return $response;
        }

        $ym = substr($ym, 0, 4) . '-' . substr($ym, 4, 2);
        $startTime = strtotime($ym);
        $endTime = strtotime('+1 Month', $startTime);
        $map = [
            'status' => 1,
            'create_time' => ['gt', $startTime, 'lt', $endTime]
        ];
        $albumList = $this->albumService->queryAllBy($map, ["createTime" => "desc"], ["id", "createTime"]);
        $data = [
        ];
        foreach ($albumList as $album) {
            array_push($data, [
                'url' => self::SITE[$site]['url'] . '/album/' . $album['id'],
                'datetime' => date(DATE_ATOM, $album['create_time']),
                'changefreq' => 'monthly',
                'priority' => 0.8
            ]);
        }
        if (count($data) == 0) {
            array_push($data, [
                'url' => self::SITE[$site]['url'],
                'datetime' => date(DATE_ATOM),
                'changefreq' => 'daily',
                'priority' => 1
            ]);
        }
        $response = $this->getResponse($data, $format);
        if ($cache) {
            @file_put_contents($fileName, $response->getContent());
        }
        return $response;
    }

    /**
     * 支持xml,txt
     * 最新n条数据
     * @Route("/{site}/newest.{freq}.{format}", name="sitemap_album_newest", methods={"POST","GET"})
     * @param Request $request
     * @param $site
     * @param $format
     * @param string $freq
     * @return Response
     */
    public function album(Request $request, $site, $format, $freq = 'daily')
    {
        if (!array_key_exists($site, self::SITE)) {
            return new Response('不支持的站点');
        }

        $size = $request->get('size', 5000);
        if ($size > 10000) $size = 10000;

        $albumList = $this->albumService->queryBy(['status' => 1], new PagingParams(0, $size), ["createTime" => "desc"]);
        $data = [];
        foreach ($albumList as $album) {
            array_push($data, [
                'url' => self::SITE[$site]['url'] . '/album/' . $album['id'],
                'datetime' => date(DATE_ATOM, $album['create_time']),
                'changefreq' => $freq,
                'priority' => 0.8
            ]);
        }
        return $this->getResponse($data, $format);
    }

    protected function getResponse($data, $format)
    {
        $response = new Response();
        if ($format == 'xml') {
            $content = $this->getXmlSitemap($data);
            $response->setContent($content);
            $response->headers->set('Content-Type', 'text/xml');

        } elseif ($format == 'txt') {
            $content = $this->getTxtSitemap($data);
            $response->setContent($content);
            $response->headers->set('Content-Type', 'text/text');
        } elseif ($format == 'sitemap') {
            $content = $this->getSitemapIndex($data);
            $response->setContent($content);
            $response->headers->set('Content-Type', 'text/xml');
        } else {
            $response->setContent('仅支持xml,txt格式站点地图');
        }
        return $response;
    }

    protected function getSitemapIndex($data)
    {
        return $this->renderView('sitemap/sitemap.xml.twig', ['data' => $data]);
    }

    protected function getTxtSitemap($data)
    {
        return $this->renderView('sitemap/index.txt.twig', ['data' => $data]);
    }

    protected function getXmlSitemap($data)
    {
        return $this->renderView('sitemap/index.xml.twig', ['data' => $data]);
    }
}
