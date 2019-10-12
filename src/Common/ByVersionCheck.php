<?php


namespace App\Common;


use by\component\exception\NotSupportVersionApiException;

class ByVersionCheck
{

    /**
     * 检测版本，只会检测不支持的版本，如果不是这些版本那默认支持，避免写太多的版本历史
     * @param $ver
     * @param $serviceType
     * @param $his
     * @throws NotSupportVersionApiException
     */
    public static function checkVersion($ver, $serviceType, $his)
    {
        if (array_key_exists($serviceType, $his)) {
            $verHis = $his[$serviceType];
            foreach ($verHis as $key => $vo) {
                $exp = substr($key, 0, 1);
                $satisfy = false;
                // 只有 等于， 小于
                if ($exp === '=') {
                    $satisfy = (intval(substr($key, 1, strlen($key) - 1)) == intval($ver));
                } else if ($exp === '<') {
                    $satisfy = (intval(substr($key, 1, strlen($key) - 1)) > intval($ver));
                } else {
                    $satisfy = (intval($key) === intval($ver));
                }

                if ($satisfy) {
                    if ($vo['status'] == 'not_support') {
                        throw new NotSupportVersionApiException('service version ' . $ver . ' is not support');
                    }
                }
            }
        }
    }
}
