<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Controller;


use App\Entity\Picture;
use App\Helper\OssHelper;
use App\ServiceInterface\PictureServiceInterface;
use by\component\exception\InvalidArgumentException;
use by\component\exception\NotLoginException;
use by\component\paging\vo\PagingParams;
use by\infrastructure\base\CallResult;
use by\infrastructure\constants\StatusEnum;
use by\infrastructure\helper\CallResultHelper;
use by\infrastructure\helper\Object2DataArrayHelper;
use Dbh\SfCoreBundle\Common\ByEnv;
use Dbh\SfCoreBundle\Common\LoginSessionInterface;
use Dbh\SfCoreBundle\Subscriber\CallResultSubscriber;
use PHPImageWorkshop\ImageWorkshop;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class PictureController extends AbstractController
{
    /**
     * @var LoginSessionInterface
     */
    protected $loginSession;
    /**
     * @var PictureServiceInterface
     */
    protected $pictureService;
    protected $uid;

    public function __construct(
        LoginSessionInterface $loginSession,
        PictureServiceInterface $pictureService)
    {
        $this->pictureService = $pictureService;
        $this->loginSession = $loginSession;
    }

    /**
     * @Route("/picture/query", name="file_query", methods={"GET","OPTIONS","POST"})
     * @param Request $request
     * @return mixed
     */
    public function query(Request $request) {
        $pageIndex = $request->get('page_index', 1);
        $pageSize = $request->get('page_size', 10);
        $date = $request->get('date', '');
        $paging = new PagingParams(intval($pageIndex), intval($pageSize));
        $map = [
            'status' => 1
        ];
        if (!empty($date)) {
            $beginTime = strtotime($date);
            $endTime = $beginTime + 24 * 3600 - 1;
            $map['createTime'] = ['gt', $beginTime, 'lt', $endTime];
        }

        $ret = $this->pictureService->queryAndCount($map, $paging, ['id' => 'desc']);
        if ($ret instanceof CallResult && $ret->isSuccess()) {
            $data = $ret->getData();
            $list = $data['list'];
            $cdnPath = ByEnv::get('CDN_IMG_URI');
            foreach ($list as &$vo) {
                if (strpos($vo['relative_path'], 'http') !== 0) {
                    $vo['url'] = $cdnPath.$vo['relative_path'];
                }
            }
            return CallResultHelper::success([
                'count' => $data['count'],
                'list' => $list
            ]);
        }
        return CallResultHelper::fail();
    }

    /**
     * @Route("/picture/upload", name="file_upload", methods={"GET","OPTIONS","POST"})
     * @param Request $request
     * @return \by\infrastructure\base\CallResult|string
     * @throws InvalidArgumentException
     * @throws NotLoginException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \PHPImageWorkshop\Core\Exception\ImageWorkshopLayerException
     * @throws \PHPImageWorkshop\Exception\ImageWorkshopException
     * @throws \ReflectionException
     */
    public function upload(Request $request)
    {
        $this->checkLogin($request);

        $fileType = $this->getValidFileType($request);

        $file = $request->files->get('image');
        $ossType = $request->get('oss_type');
        if ($file instanceof UploadedFile) {
            $publicPath = $this->getParameter('public_dir');
            $uploaderDir = $publicPath . $this->getParameter('uploader_dir');
            if (!is_dir($uploaderDir)) {
                return $uploaderDir . ' not exists';
            }
            $size = $file->getSize();
            if ( intval($size / 1024) > 1024) {
                return 'image size must be smaller than 1MB';
            }

            $imgSizeArr = getimagesize($file->getRealPath());
            $entity = new Picture();
            $entity->setStatus(StatusEnum::ENABLE);
            $entity->setUid($this->uid);
            $entity->setSize($file->getSize());
            $entity->setExt($file->getClientOriginalExtension());
            $entity->setOriginalName($file->getClientOriginalName());
            $sha1 = sha1_file($file->getRealPath());
            $md5 = md5_file($file->getRealPath());
            $entity->setSha1($sha1);
            $entity->setMd5($md5);
            $entity->setW(0);
            $entity->setH(0);
            if (is_array($imgSizeArr) && count($imgSizeArr) >= 2) {
                $entity->setW($imgSizeArr[0]);
                $entity->setH($imgSizeArr[1]);
            }

            try {
                if ($fileType == 'avatar') {
                    $path = $this->checkAvatarPath($uploaderDir, $fileType);
                    $saveFilename = 'avatar.png';
                } else {
                    $path = $this->checkPath($uploaderDir, $fileType);
                    $saveFilename = uniqid(date("His")) . '.' . $file->getClientOriginalExtension();
                }

                // 写入数据库
                $relativePath = str_replace($publicPath, '', $path . $saveFilename);

                $entity->setRelativePath('/' . $relativePath);
                $entity->setCategory($fileType);
                $entity->setSaveName($saveFilename);
                $entity->setOssKey('');
                $entity->setOssType('');

                $smallerImage = '';
                $this->pictureService->safeInsert($entity);
                if ($entity->getSaveName() == $saveFilename) {
                    $trueFile = $file->move($path, $path . '/' . $saveFilename);

                    if ($fileType !== 'avatar') {
                        $smallerImage = $this->cropImages($imgSizeArr, $trueFile->getRealPath(), $uploaderDir, $fileType, $entity->getSaveName());
                    }
                }

                if ($ossType) {
                    $entity->setOssType($ossType);
                    $ossPath = $relativePath;
                    $ret = $this->uploadOss($ossType, $ossPath, rtrim($publicPath, '/') . $entity->getRelativePath());
                    if ($ret->isSuccess()) {
                        $ossKey = $ret->getData();
                        $entity->setOssKey($ossKey);
                        $smallerImage = $ossKey;
                        $this->pictureService->flush($entity);
                    } else {
                        return $ret;
                    }
                }
                $cdnImgUri = ByEnv::get('CDN_IMG_URI');
                $entity->setRelativePath($cdnImgUri . $entity->getRelativePath());
                $arr = Object2DataArrayHelper::getDataArrayFrom($entity);
                $arr['smaller_img'] = empty($smallerImage) ? "" : $cdnImgUri . $smallerImage;
                return CallResultHelper::success($arr);
            } catch (FileException $exception) {
                return $exception->getMessage();
            }

        } else {
            return CallResultHelper::fail('Need File Called `image`' . $file);
        }
    }

    protected function uploadOss($ossType, $ossPath, $filePath)
    {
//        if ($ossType == 'aliyun') {
//            $ossClient = OssHelper::getAliyunOssClient();
//            $ret = $ossClient->putFile($ossPath, $filePath);
//            if ($ret->isSuccess()) {
//                return CallResultHelper::success(rtrim(OssHelper::$aliyunOssCdnUrl, '/') . '/' . ltrim($ossPath, '/'));
//            } else {
//                return $ret;
//            }
//        }
        return CallResultHelper::fail('invalid oss type');
    }

    /**
     * @param Request $request
     * @throws NotLoginException
     */
    protected function checkLogin(Request $request)
    {
        $sid = $request->get('sid', '');
        $this->uid = $request->get('uid', '');
        $deviceType = $request->get('deviceType', '');
        // TODO: 正式调用验证，目前直接返回验证成功
        $ret = $this->loginSession->check($this->uid, $sid, $deviceType);
        if ($ret instanceof CallResult && $ret->isFail()) {
            throw new NotLoginException('Please Login Again ' . $ret->getMsg());
        }
    }

    protected function getValidFileType(Request $request)
    {
        $fileType = $request->get('t', '');
        if ($fileType == 'avatar') {
            return 'avatar';
        } elseif ($fileType == 'pic') {
            return 'pic';
        } elseif ($fileType == 'album') {
            return 'album';
        } elseif ($fileType == 'banner') {
            return 'banner';
        } elseif ($fileType == 'other') {
            return 'other';
        } elseif ($fileType == 'id_card') {
            return 'id_card';
        } elseif ($fileType == 'cms_article') {
            return 'cms_article';
        } elseif($fileType == 'brand_icon') {
            return 'brand_icon';
        }  elseif($fileType == 'bank_card') {
            return 'bank_card';
        }   elseif($fileType == 'video_cover') {
            return 'video_cover';
        }   elseif($fileType == 'goods') {
            return 'goods';
        } else {
            throw new InvalidArgumentException('Invalid File Type');
        }
    }


    protected function checkAvatarPath($path, $fileType)
    {

        $path .= $fileType . '/';
        $oldMask = umask(0);
        try {
            if (!file_exists($path)) {
                if (!mkdir($path, 0777, true)) {
                    umask($oldMask);
                    throw new InvalidArgumentException('cant create path ' . $path);
                }
            }
            $path .= $this->uid . '/';
            if (!file_exists($path)) {
                if (!mkdir($path, 0777)) {
                    umask($oldMask);
                    throw new InvalidArgumentException('cant create path ' . $path);
                }
            }
        } catch (FileException $exception) {
            umask($oldMask);
            throw $exception;
        }
        return $path;
    }

    protected function checkPath($path, $fileType)
    {

        $path .= $fileType . '/';
        $oldMask = umask(0);
        try {
            if (!file_exists($path)) {
                if (!mkdir($path, 0777, true)) {
                    umask($oldMask);
                    throw new InvalidArgumentException('cant create path ' . $path);
                }
            }
            $path .= date("Ymd") . '/';
            if (!file_exists($path)) {
                if (!mkdir($path, 0777)) {
                    umask($oldMask);
                    throw new InvalidArgumentException('cant create path ' . $path);
                }
            }
        } catch (FileException $exception) {
            umask($oldMask);
            throw $exception;
        }
        return $path;
    }

    /**
     * 缩减图片
     * @param $imgSizeArr
     * @param $filename
     * @param $basePath
     * @param $fileType
     * @param $saveFileName
     * @return string
     * @throws \PHPImageWorkshop\Core\Exception\ImageWorkshopLayerException
     * @throws \PHPImageWorkshop\Exception\ImageWorkshopException
     */
    protected function cropImages($imgSizeArr, $filename, $basePath, $fileType, $saveFileName)
    {
        $cropSize = [
//            'mini' => 60,
            'small' => 160,
//            'large' => 480
        ];
        $smallerImageUrl = "";

        if (is_array($imgSizeArr) && class_exists("PHPImageWorkshop\ImageWorkshop")) {
            $origin = ImageWorkshop::initFromPath($filename);
            foreach ($cropSize as $key => $cropWidth) {
                $savePath = $basePath . '/' . $fileType . '_' . $key . '/' . date('Ymd') . '/';
                $origin->resizeInPixel($cropWidth, null, true);
                $smallerImageUrl = '/'.rtrim($this->getParameter('uploader_dir'), '/').'/' . $fileType . '_' . $key . '/' . date('Ymd') . '/'.$saveFileName;
                $origin->save($savePath, $saveFileName);
            }
        }
        return $smallerImageUrl;
    }
}
