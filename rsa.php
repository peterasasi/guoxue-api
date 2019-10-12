<?php

// PHP 代码参考

function verifySign($data, $base64Sign, $key) {
//$key pem格式的公钥
    return openssl_verify($data, base64_decode($base64Sign), $key, OPENSSL_ALGO_SHA256);
}

// 解密
function decryptChunk($encryptData, $privateKey)
{
    $crypto = '';
    $chunk = '';
    foreach (str_split(base64_decode($encryptData), 256) as $chunk) {
        openssl_private_decrypt($chunk, $decryptData, $privateKey);
        $crypto .= $decryptData;
    }
    return $crypto;
}

$userPrivatePemKey = '-----BEGIN RSA PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDF6/EW5ryj+mHa
8jKQAhTEBUsxBvQo5Y95cZgkFNwKgLzbmySQqZ2tB2hrFHsyot55NPnR29XqL/xm
WaIqUVPrvbxKZJrKP8GYSeVVprb1p3I0UkGtzLBzGoS0hUTCQ7UCfoPaWgjnjFzu
pZ1RO7IQH0mawflTVRRXdCm7x8nFm27AmgzsaYdkOb2BXHaRPCr2sznwWS3O92cD
Z6qcT2lCwOU3z8KDRtQpdeLn6YV85QJphlKM9VibnrE7bgk8YLMDf5gNWHlPWXP1
heHyxcqGIB5smnlVToTMkOyNTSey0XCaQNV5f5x/ET5q0SzkAk4pyzEOiZYyI5M6
oixfInEPAgMBAAECggEBAMNcVhfl31P4hHiqUpBlDXxDQWn+VCi+FzWnk6Yh1OSl
GEWoSJpmYhX11vLDP6vGqdR4HxMvbGIBxaB9Xx1oM27hVKcV0NhLwFsCrdUyfyvR
gV/1xZC5F342McFCPpGGZXg1yw5PirSLjvudC8CwLN3PFEcmqmS/+Rkttzf8R8S9
H/DpfSMDP6/1+fipc+VEr1Dj5YLSaXkHOE8ou5MhIlI6aAyXh1bmIqAuEvOo0oqC
ZV2m9tEX2dc1iwJwUXl9RWaN9NePPjWJ3ifpqH7IAD/mn06hz4nKkMfJNgk4Y48D
izEM9uuUkD0cTyNqVf2KE6M8zK/kJPPPQMkceR9/0JECgYEA60w17Mu6SgsSTOeC
uTKr+kPfioVtXzlhKFKCIL6caP9saVFhH3zwFUuIfFCM5nGk1uK5YSpOLcbZyRzd
NSNfcr7HLGBYfkqry20ZRtvecJ9FZPrW0WfnI5I+Hc1Om5soq5me7q32s1jMDC9W
wsAi8zdCsVoZ5jygHLal1GZimWcCgYEA11XhwLqL68sc0Ybmnmx0kr2iOmH7TpKb
smRTJswO7PQUjRhTIhhV0EInTw3AjXnK+OQ4tHUCOzDWIugWQKFJaBy4uUhAj+yQ
zV2By8qZZPF8Beh/YNqmOZXPgoRWvyEd9YeYR/6VU2gopYY1MLLyqqn3+ZwURKWB
i5NMwMeLGhkCgYBwDpRj2EY4uvsVKvAjmNE0V5yfEJoczmvJ0zlrtLsyeeo8Yeg6
IbsIuTcf1RC1MowJVmJotsgSnu/bCmcCxnzPXbHnHV8njhIwyB1QlKdjxUY8KAWk
JrJ6S0xPzxXPn14IExoQm0Kw+On8J5NPHkfGwpt9cOaCsn6iDg39kGEGowKBgHHs
DbNY/g2RN33iUMAfwMyhZuJcVAgNqDGIwjUUYBEcUIMVC2ZOWZgEZ6d7LxOqC6gB
lb5i6PtHqwJsptkqexuJlnDvuLhr/2+muLSISv8MgwDdMAadprNwfogeW4ZijucH
BZmRJo8p29c2WY2aHqgLpBV6bX6j0RV2qHSVHRKBAoGAFz8hhXPfgyl9i7patcSS
8KK6bHGUsRuMDefoUnV/TCs6lgFiRl17SMQTV5iQXertHF/vG+K7bDTispLY5wBx
nIAqxxj/GLDrD7ay7GYjHihBplsKZEeEFb37xzRU0ysngNyKErQWLRYsfq4vCXGk
EqLRFQ5TZBDTZKCgPqJpmGs=
-----END RSA PRIVATE KEY-----';
$sysPublicPemKey = '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAoZC02j8JOimrdP0RSpKN
EZzyb7Vw5YI28jTgWa55gi2XgU8PtD48/nGWwHVviVhR/8x+hWzzeemmtkkaO7ep
LulUjwmVyVMco4PhJBaYsLJnVdD+O2i2r6VfX7FOV9dmO5ASjyz/5zOH92HjIiKm
ayEN7VbHO4e8RZolZOjXcCm8W9ycJMfwsKFjB0IPQ2n0gOUfqUwYeGYCVsBHm2SL
mYBGv0O/F5hqWIqiPvyAiadXJ1f2166hOtCQrOvgTTUVYcdLkWsdb/6HIfbuzYcw
aYrzbvFH3kD2dL9jKXIX1km/42MemwcK32YDoJFtUGtRn8t6srygJPBNtAfOahmo
lQIDAQAB
-----END PUBLIC KEY-----';

// 1. 第一步先解密
// 接收数据如下:
$content = "Vw0iuAgW9Sx0Ate0Iwije/7ssm5kXJwU6/QjcJsvvMZzixmaFX4MBcvWEoSNWg7ce8pFzfLFqGE9wfde4Fa7uCMO+qegE+WaiwTss8nzNQUFRAGfBmNfr51wcbHQVD7QZkH5LoCDcCCmkRl0q5JJvQ6Fu4lW7dxzO7ByZZS6BhzI6y6CZIAGjR16Zk+SDzF0hCCZTsSZ1ArSGpWAJhgnCzEMyM9hAYHAMs3DYfjCoS8eeCv9zxeunAvrGSoEM5nU4+QP2LeMz61SWI2NpdDUAp1zxfnIEzewoXq4CClT7gDNids4ozl79AIUEQYmDqmQNoK+MwhpQVBtaufv8W9kEarGFp8WDFBoul9CmPX95CkZp7ETk3jN8nALjdgteGr8s9dSfxRIMZH9GDf9jx4jtSj1fBmvNTQF6ZVEs5muzcI9OOuRQoAwZ/wJuCSlSsiqt4pLeDopXT8kdRWdd8lYVTiRWD0N7vSGK3J8oUX8kM7fQ93CkYo/UxWUawSsTonebfjXxSAK2iRYGEjvxXdRksU6DRTZ0ji6jGiHqsQ+N/wfb9o+I1qMyOdyWIy7QniiCtPg2Qr8UcjMpqgRCN8cY7PVgZhZPJMRbr35QihIvKU6WlMiQmZrMK3A7RseQ31gU8IHPRzHkZDNqVBq7a2awdlu+DAtwd5oSNAzTX8ZA1c=";
$client_id = "byy323";
$alg = "rsa_v2";

$decryptData = decryptChunk($content, $userPrivatePemKey);

echo '解密内容:'.$decryptData,"\n";
// 得到解密后得json格式得字符串
// {"client_id":"byy323","money":"10000","order_no":"23324324","pay_code":"abadsf","trade_no":"trade_no","sign":"J3nLsgcBUJvQ0RjvvXgi5FIoQrBSvsMWenqI2Rh0ggARV4kSPrY\/5\/j+oO5fVlVL8G0TbI75cGra40uXBv1KeqrxSc5MWU4Nmapwi8XBDjzJW0byurrCZbj0R+4u6SvAkMpqQLBsnYDndXd5GBX4h69EuTfoyrlg6O6dNItdBAgmE2tx71q0g1hrZjutPJtt9gEhyM8IekKaOpnuYru7xUTvTU0sy0dpDDyZj9nmRcTQ+oXxGcTem\/VwHhfOLxWY\/b1WXOh23VvtTss3W25Z7F9EhrFGSn05531x2BOM5HL\/IznPd5ggq5eyCi2AwzB2m9Zw6aUTSKPsfNR1jAVDNA=="}

// 2. 验证签名

$decryptData = json_decode($decryptData, JSON_OBJECT_AS_ARRAY);

// 这时得到原始数据数组

/**
 * $decryptData = [
'pay_code' => 'abadsf',
'money' => '10000', // 这个请使用字符串类型，不能用integer类型
'trade_no' => 'trade_no',
'order_no' => '23324324',
'client_id' => 'byy323',
'sign' => 'J3nLsgcBUJvQ0RjvvXgi5FIoQrBSvsMWenqI2Rh0ggARV4kSPrY/5/j+oO5fVlVL8G0TbI75cGra40uXBv1KeqrxSc5MWU4Nmapwi8XBDjzJW0byurrCZbj0R+4u6SvAkMpqQLBsnYDndXd5GBX4h69EuTfoyrlg6O6dNItdBAgmE2tx71q0g1hrZjutPJtt9gEhyM8IekKaOpnuYru7xUTvTU0sy0dpDDyZj9nmRcTQ+oXxGcTem/VwHhfOLxWY/b1WXOh23VvtTss3W25Z7F9EhrFGSn05531x2BOM5HL/IznPd5ggq5eyCi2AwzB2m9Zw6aUTSKPsfNR1jAVDNA=='
];
 **/
// 把sign参数单独存储，并从数组中去掉
$base64Sign = $decryptData['sign'];

unset($decryptData['sign']);

// 按key的ascii码排序对参数数组进行排序，
ksort($decryptData);

// 排序后的$decryptData如下
/*
array(5) {
  ["client_id"]=>
  string(6) "byy323"
  ["money"]=>
  string(5) "10000"
  ["order_no"]=>
  string(8) "23324324"
  ["pay_code"]=>
  string(6) "abadsf"
  ["trade_no"]=>
  string(8) "trade_no"
}
*/

// json编码，不解析unicode编码对于含中文有影响
$strBody = json_encode($decryptData, JSON_UNESCAPED_UNICODE);
// json编码后如下，这个就是签名前得明文
// {"client_id":"byy323","money":"10000","order_no":"23324324","pay_code":"abadsf","trade_no":"trade_no"}

// 使用verifySign函数签名后
$verify = verifySign($strBody, $base64Sign, $sysPublicPemKey);
if ($verify == 1) {
    echo '验签成功',"\n";
} else {
    echo '验签失败',"\n";
}
