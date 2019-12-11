<?php

$decode = openssl_decrypt("u41ZWvYoilr8g+WKkA5qTc22fr+TQ7Au", "des-ecb", "5dc542949c724860b4c651a62882fedb");

var_dump($decode);
//$str = http_build_query(['a' => 'b', 'c'=>'d']);
//var_dump($str);
// 股票交易手续费
//1 - 6
//1-2 => 给2
//2-3 => 给3
//3-4 => 给4
//4-5 => 给5
//5-6 => 给6

//$tax = 0.001;
//$per = 0.0005;
//$saleAmount = 6000;
//
//$fee = $saleAmount * $tax;

//var_dump('05' > 6 || '05' < 3);
//
//$params = [
//    'out_trade_no' => '222',
//    'total_amount' => '100',
//    'trade_no' => '123123'
//];
//$body = [
//    'pay_code' => 'abadsf',
//    'money' => '10000',
//    'trade_no' => 'trade_no',
//    'order_no' => '23324324',
//    'client_id' => 'byy323'
//];
//
//// 排序数组，按key的ascii码排序
//ksort($body);

//var_dump(md5(json_encode($body, JSON_UNESCAPED_UNICODE)));

//$url = "http://www.baidu.com:8080/index.php/index?a=3&b=3";
//function getSchemeHost($url) {
//    $parseUrl = parse_url($url);
//    $sh = '';
//    if (array_key_exists('scheme', $parseUrl)) {
//        $sh .= $parseUrl['scheme'].'://';
//    }
//    if (array_key_exists('host', $parseUrl)) {
//        $sh .= $parseUrl['host'];
//    }
//    if (array_key_exists('port', $parseUrl)) {
//        $sh .= ':'.$parseUrl['port'];
//    }
//    return $sh;
//}
//var_dump(getSchemeHost($url));
//$mt = explode(" ", microtime());
//var_dump($mt);
//$time = str_pad(intval($mt[0] * 1000000), 6, "0", STR_PAD_RIGHT).$mt[1];
//var_dump($time );
//var_dump(strlen($time ));
//$reg = '/^.{3,64}$/i';
//
//preg_match($reg , "abcdd@126.com.abc.vip.com.dee", $match);
//
//var_dump($match);

//var_dump(AMQPConnection::class);

//var_dump(json_encode(['nickname' => '天天向上']));
//var_dump(json_encode(['nickname' => '天天向上'], JSON_UNESCAPED_UNICODE));
//$sTime = microtime(true);
//var_dump($sTime);
//usleep(333);
//$eTime = microtime(true);
//var_dump(intval(1000000 * ($eTime - $sTime)));
////usleep(100);

//var_dump(microtime());

//var_dump(Redis::class);
//return $cardCount * ($money * $userPayFee->getRate() / 100 + $userWithdrawFee->getRate());

//0.006
//提现: 0.6

//$balance = 508 * 0.006;
//$thirdMoney = 508 - $balance;

//var_dump(508 - $thirdMoney);// 3.048 + 0.6 + 3.0186 + 0.6
//1.2 + 3.048 + 3.0186 = 7.248 + 0.0186
//    7.2666;

//var_dump(503.7);//

// 500.6 / (1-0.006) = $x;
//var_dump( 503.1 * 0.006);

//var_dump((500.6)/ (1-0.006));
// 500 * 0.006 + 0.6

// (500 * 0.006)

$fee = 500 * 0.006 + 0.6;
//var_dump($fee);

//var_dump(substr(date("Ym"), -4, 4));
///$key = '5dc542949c724860b4c651a62882fedb';
//$content = '447';
//$de  = openssl_encrypt($content, "des-ecb", $key);
//var_dump($de);
//require_once  './vendor/autoload.php';
//$priv = 'MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDrx0t8Dx5KQu2d0vJYsXQr3GvHJgUco5ZLUFjusaZz2rRRl+Nm6WuxQY7Fx99te4q7KIO0bqi4VgV2Hqzuh1/ypERfdyclkpJ29r4fwmmd/fdVDNDCSwF20TvXUrEdCHRDhnueCexU+bO/q8SKC/oAQju69b5H0tvbDsBj2/ZN/T2D1N6C155DqGMtoRfi2xeqnslYIkgJa0ysNUHb9P//sU6uGUeVKvo17HrcKheZ6BavxGR3cYsuJjFN0YzzyL3pLDqqR+f1yLo2X5qZftsdzELrUo2Wmq/inJUt9VsuiXHnhAF9uy3p8qZet1xFIF2Wncmnm4ITLxAM9OAExixHAgMBAAECggEAA5vCOPzWPta1YkP3N4pHt+AguiSH0X9jbnWlVsnbPUysk88aSNnrrwPM1pQPhFJuthyRn3JOHLwcS0l+5gumWUVQUpG1RKrwmOd/02abTTK01VVj4IVbWWEsZEg8s7AMb3ggbjPCrS5BJP7Q/DfgtJ5O4bafGeRNPxYDtcOJCnp9L8DMgQIGlZfnG0oVh4gSLJu5bC23P8/cIsSlUnN2t7Ld3tkXZBE1AWMaPcQNac9wLOebArAXlUKX/sDa/8OPIBPL5cirKskllJJu3+mJ8SNwBf4vIgQ3tM2IqCxs+Yyxbr6sqDuWr0cNgDKNCSLK3yG4vVAH3JruT34sJwyg8QKBgQD8Cgf0twW0b+8WfMi4Aqr1/M7WfoULy29QBOtOnUN3pUZ/2JmDuRt+GXfVte7Igud33jvYI+tgZn5Y70lKuZNDDZg3gmfkFniUfK6eAPB8wImciu3cWxjyw5+8oBpVNn29GDu7ihmrSMB8YA2pC0ZDaQo/bu37UfDAMY0QJO8ZGQKBgQDve9iWo5ztwLZQNwy+aRh4SQVI44hzv52QRhmwF/PZu0HTPuurD7hubp0RJaVw0NnUEy88358cZZm9/dYrGSQm+ntC0PNs6bKTCZOFUDgi+iHwSNvayNEv+KQpicAvlBQiMYUr8QQNQYzULBTWyJJhJwtHFgZNrOCpl2PRmro8XwKBgGxL4x9DhTAC6LjA5X4rp0oLVtTTiFeJEktP0cv1xweh/KjyaQQwhZ+pUdSCWBfQJ6VZ2F0HEhxZ7fBI4TU6iAxHyyAO6JUZEra63E0IGk2AbaDWEV6dxhqJ9BkYsjfrMhwOTZxHur/egtbubvAz5/0PJVgcUiWrD8eFgxdsx1ABAoGAFZewBp0H6DPo/ECKaJynNSk4TcMeKXmMZla7uDHgrbABll9k9k57jZFxnfsr+IkMKt/z8WQkP3Y1r3i0l7wzk0QgWvzmBdroW/OQFoQOG7E74CNhl09l9RJREuG1r1SFoDOg0z5u5BcV8Ids9ZSuBg50KNumg9hjhUb+HAb8ZbcCgYEAmcqHlb//gYT27uoA+Pi26h6lhOyU/bt45rId2dbrROjlJQzJPfsSJL8MghZZdwNy0poFr5h7FOQUHWR68Stcg0l5NcJ6mL0+f+8oi54JHeF7r6pIkAYgauE9QAwKyzlpYW7FeZ1LgNlKKpr4mq7ovGvKbzHRwTuMLuk8mV2xZ+c=';
//$priv = \by\component\encrypt\rsa\Rsa::formatPrivateText($priv);
//var_dump($priv);
//
//openssl_sign('a=123', $sign, $priv, OPENSSL_ALGO_SHA256);
//var_dump(base64_encode($sign));

//openssl_ve
//var_dump($today = strtotime(date("Y-m-d 0:0:0", time())));
//var_dump(date('Y-m-d H:i:s', $today));

//var_dump(strpos('会员号ZMFCB100594已注册', '已注册') !== false);
//
//$reg = '/^https?:\\/\\/.*(localhost|361fit\\.cn):?(\\d*)?\\/?$/';
//$subj = 'http://admin.361fit.cn';
////
//$total = number_format(5 + (1000 * (0.95 / 10000)), 2, '.', '.');
////
//var_dump(ceil(300 + 0.004 * 1000));
//var_dump(ceil(200 + 0.55 / 100 / 100  * 1000));
//var_dump(($total));
//var_dump((800 * 0.4 / 10000));

//var_dump(preg_match_all($reg, $subj, $matches));


//function hideSensitive($str, $start = 0, $len = 0, $replaceChar = '*', $replaceCount = 4) {
//    if (strlen($str) > $start + $len) {
//        return substr($str, 0, $start). str_repeat($replaceChar, $replaceCount).substr($str, $start + $len, strlen($str) - $start - $len);
//    }
//    return $str;
//}
//
//var_dump(hideSensitive('13484379290', 3, 4));

//$a = json_decode('{"partnerCode":"P606617061300000001","signature":"OwCyQgW+MxUChAtXeow2t4AATbQKugO\/wigbTSSte7rVXk+WDAzcj0TipXRC0fNp17MZ1N7QZMofKAR813rx0RAZCMiDWk2P9iuDaCIMkt5ULd++5KeGkfpV0OZS4ArQzzMg0JPyrkc+gHXdNAECTB2X9i+BXINkMkA3auW8SMhph3idlAqtEEl6g65pPBLc4Kc79Njz7XZcoVXtNX9vUAfEVwPAFSsNLkQLLHAnqNKs05H8ATsWcPuE8AkNXhaz62yHu0sV7f6VykhZDRNbiCax6rUxLaN\/S8x4fTBOSEacaGRBWwhJLcGtMo0pDFECvEpHhzfRG+fLVRSN8miVXQ==","enctypeData":"fJOtUGJRtCuMfJxAAVsfoOz0pNR1N0+ASohZNYQap\/YfI03QH6ft2l765QE9wnnT9pWQ6EfGWNxKWf8lNPYbBSRLlvhDb2j8gK3yl6onWYiyiATCZYp5Ks283gXcwMIexZyEKu5r98eZwv1ZFTBpQY22dvha\/SSQLF8aC3X1oCIQlXGiAh03Lp6LEyKzq2b39HfYVOoIPgTNGxucvt5RpU4tgWKhCDmS8ocuPJt50lYAnZONnbczm7Oqg00tqE92S8YlOwCgS6a29ZFyIPo62PqSXelW4EH9ZiLjenohKJTzUNy02buZt2OX3q7LVb5n6hHHslU\/TBqXqrY2XPVETA==","appId":"6D2CE03E3FE7E7DC1B8603ED2D57D022"}', JSON_OBJECT_AS_ARRAY);
//
//var_dump($a['signature']);

//// 用户私钥
//$userPrivateKey = "MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDH8NFjIzuaxLyCOieDz5icyNwg1mb/QnwzqO7z599rLVtZ2i+xfN805/ActRJJB8+D/PqoM7icA1r2oqZgYetLki8QP94Oqdxs8UzH73uWdbdb6zCOWYyxk2Jftjkp3zL97fqahwhOmY3mRaeN+dS+6REJ4IQVIHujmNRLHaUTaUNRbj/HfS6aV/SONAhSoj0/WkFOf4eXbAngvEv0Du3m/Fb7UCvMkZvj5xJ4G0mlbBmAK/m2bryfa9wcP0fnIli1ReJ1DFlm0Za5t+eUpZAQQfT3YaxbGRmXq8kiMJaPfGDYrzMLVsja2dIqFEXrglWQcMXtY+Ursd5JVpvdbEA/AgMBAAECggEAF5FGTSZBB1w7UCpkr//PYGO4ttIu79W5aCl4iR2C01JUW0IBry0l7kmMnwWk8yDNkCRIs3ztPM6UcU/4xpGkN5Myovq0RQw2pEzJHSQYcELN6zLM1Wquz9usk9WZ5Vqe2xmrGX3jN8iX1lXNi0mwjxRP4tcpGohqqn0AQR5sb94Z/hSvOcR4/kccXMQIGkhnejKqXkXzJOHMFGqBOWVThs4OSnAPhPlXVTAZ89sK00C+JZImYrqOyoSoaq52p2pQOe09OrIBdeGVSJ3GVFt+fIB0NTBPXG2xjZr0v2EYrNl1tGWcCqktWEEjKt08pV4D68Db2jQhf4Q7YzT1km2mcQKBgQD2fpPFE2Mb2uDrturbaFkLp6B7odIIwdDE1N50zPHk1qK+49WcMQHT0OFSZauUvdyqPmkgOI2A3cjf3Wdk4Tl2K3SeeVLdErZfqWRzDML3BPEjH5x531hR4gvpWMcN0GkmGKiWLRkCYMHcydO5Ibbz+WlYnRFCSgycVcumSOnNCQKBgQDPpqgQgrwIQEYAISPU/I/atwmV7EtbSBqw6EF3+vypOJhB0GqwUJsBa7A/IQSLkfnlwIquHdgrUR1FX1La6HM4Om8JMlSBZw96dlOOcPVtEZS7QPhQDV1c/dFfVAncD13oI5DBYObZ4dYY1beRo8MNwWvaCpC/uWPxiovk/2i9BwKBgQDQuKHb8OytO4vVTNBV9WfhTJHB3maBb8ydvzqXYKs7gNvSFA5e8ciAWZFSOjEuBA8EQVC3Lev0QNjFZy8T5vrHK0jWoBkghaXUHxWlrhqxHIgrm6reL9cTjvtTHg9/jQhcb+jhMVLKBrBhiq0zSG8o6/reRDHHFfjTsHp/VaJUMQKBgQDPCHy8qXxMRbkFXAVbz8yl5qT6A7RGeKeUBp1vwKC1H6Y+yEv3KwbA7du1tXfQqGSd+9DJNRxYY/FpP1dexzBJuYkHhFTZCCZYlS1N8bXhXwwJfweU2R5jHvXns+R4siGQ2BT1mWXRiudpr3vtC3foeRbNOIeFgJPzOY2tbjHBdQKBgQCGy+oclVfmtLdQ1w32kIUFSZmfsE0UWUIk5cTFfwZv46r5IDyCW32G+ud1Yj8nTl88FijmqrvDeYW6Jucu5/e09pv3VfOqdPYXWFB8O8VtcEopiesiQ35/0iduZf2CXTH+jSRzrJG1nw57dAZmvTigcw1EdfTOgesO691cG/I+LQ==";
//OwCyQgW+MxUChAtXeow2t4AATbQKugO/wigbTSSte7rVXk+WDAzcj0TipXRC0fNp17MZ1N7QZMofKAR813rx0RAZCMiDWk2P9iuDaCIMkt5ULd++5KeGkfpV0OZS4ArQzzMg0JPyrkc+gHXdNAECTB2X9i+BXINkMkA3auW8SMhph3idlAqtEEl6g65pPBLc4Kc79Njz7XZcoVXtNX9vUAfEVwPAFSsNLkQLLHAnqNKs05H8ATsWcPuE8AkNXhaz62yHu0sV7f6VykhZDRNbiCax6rUxLaN/S8x4fTBOSEacaGRBWwhJLcGtMo0pDFECvEpHhzfRG+fLVRSN8miVXQ==
//$data = [
//    'notify_id' => '666666',
//    'client_id' => 'by2204esfH0fdc6Y',
//    'client_secret' => 'fcbe33277447fbd48343b68d1b3f8de0',
//    'app_request_time' => '1530528254',
//    'service_type' => 'by_Clients_query',
//    'service_version' => '100',
//    'app_type' => 'ios',
//    'app_version' => '1.0.0',
//    'lang' => 'zh-cn',
//    'buss_data' => [
//        'uid' => '2'
//    ]
//];
//$transport = \by\component\encrypt\rsav3\RSAV3Transport();


//$publicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAxHZHbQKjWU7P+GWy28Q1Qh+Kg73qxvTDdOrWuXlnwBkNAY2FxbsTfG7Q5iz+8G7b/cbQofhkoBfUR28+XO5P+cZTw00PUhw2vk1LXTrvOkB47OHHoq9Sc49BIecZPdoA9JSo6TjkQMM+oylqNwUiSkMLeJRa3fhaCsiVaA7cCrStJQiR20T2V+M+uF1gmmUCeq2c9eTv2/Am4QXzJtYQoCnlwPU7k+ABDzxKODul6M3SmmzMg3XbnHcFrdW4N/d7/8EgTpe0dhuKQw/PzUNZGfKIPvg4FtupmA66ysTFyWNr0u1HtTrPiA0FVTylopShVmqYTCBAmwuRSe2I28LdiQIDAQAB';

//$publicKey = 'LS0tLS1CRUdJTiBQVUJMSUMgS0VZLS0tLS0KTUlJQklqQU5CZ2txaGtpRzl3MEJBUUVGQUFPQ0FROEFNSUlCQ2dLQ0FRRUE5Q1lpbE9zUGt2TzM2MzhTTnhtUwpCUGxQZnBObGJ5V2wvRzVxWDJCWDZWWlNmYlpUM3VSSkpvaXhRQ2pUOVRVR0ZqS3JFVlVQOWpHOFkvQVY3Rlg1CmQ5Wm1ZeGxUL2J4TnVlNm9qaGdnTHBGZmtJcmtOYytKUlFoMHEwWVQ4QmFhY1lLVUZGdEtIc2pUT01FYyt6TnoKWDNGTEl6ekxoMVEvblJOajZBaWZLdXNsMDhoMURVbEhWUGlGdFlwVHhOQUhTaXJodWtBUlVnOTNDSjZ4R1I5UQpMVm1xME9SSXhaSUYreXc0QlVaMzcyWGgxb3FRcXNVdjVZb2JHdys2SjVoVkZwVmhsa1RYWHplVmtGR2hZWkhZCmZhdjNSbVpEMGR2b3l1ZTR3aW8yZzlmdmM3bFNDM2FCQk5OSkVBVC8vcnkya1NuUnhISk1tVWdtMldwOVR5U3UKTVFJREFRQUIKLS0tLS1FTkQgUFVCTElDIEtFWS0tLS0t';
//$publicKey = base64_decode($publicKey);
//var_dump($publicKey);
//$publicKey = openssl_pkey_get_public($publicKey);
//$t = '';
//
//openssl_public_encrypt('test', $t, $publicKey);
//var_dump(base64_encode($t));
//\by\component\encrypt\rsa\Rsa::encryptPublicKey()
//$var = (14.00);
//if (strpos(strval($var), "\.") !== false) {
//    var_dump('Must Be Integer');
//}
//var_dump(strval($var));
//var_dump((strpos(strval($var), ".")));
//echo urlencode('http://api.lezchou.com/transport/callback');
//$reg = '^https?:\/\/.*(localhost|361fit\.cn):?(\d*)?\/?$';
//
//preg_match_all('/'.$reg.'/', "http://local.361fit.cn:8083", $match);
//
//var_dump($match);

//$str = '{\"notify_url\":\"http%3A%2F%2Fapidev.361fit.cn%2Ftransport%2Fcallback\",\"pay_type\":1,\"unique_order\":\"20190226074419G8215182BE0\",\"trade_no\":\"2019022622001440920500710042\",\"money\":810000,\"pay_time\":1551167129,\"payload\":{\"order_code\":\"20190226074419G1467C9F0F8\",\"client_id\":\"by04esfH0fdc6Y\",\"channel\":\"1\"}}';
//var_dump(strpos($str, "\\\""));
//$str = str_replace('\\', "", $str);
//var_dump($str);
//var_dump(strpos($str, "\\\""));
//var_dump((json_decode($str, JSON_OBJECT_AS_ARRAY)));

// 测试私钥
//MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDH8NFjIzuaxLyCOieDz5icyNwg1mb/QnwzqO7z599rLVtZ2i+xfN805/ActRJJB8+D/PqoM7icA1r2oqZgYetLki8QP94Oqdxs8UzH73uWdbdb6zCOWYyxk2Jftjkp3zL97fqahwhOmY3mRaeN+dS+6REJ4IQVIHujmNRLHaUTaUNRbj/HfS6aV/SONAhSoj0/WkFOf4eXbAngvEv0Du3m/Fb7UCvMkZvj5xJ4G0mlbBmAK/m2bryfa9wcP0fnIli1ReJ1DFlm0Za5t+eUpZAQQfT3YaxbGRmXq8kiMJaPfGDYrzMLVsja2dIqFEXrglWQcMXtY+Ursd5JVpvdbEA/AgMBAAECggEAF5FGTSZBB1w7UCpkr//PYGO4ttIu79W5aCl4iR2C01JUW0IBry0l7kmMnwWk8yDNkCRIs3ztPM6UcU/4xpGkN5Myovq0RQw2pEzJHSQYcELN6zLM1Wquz9usk9WZ5Vqe2xmrGX3jN8iX1lXNi0mwjxRP4tcpGohqqn0AQR5sb94Z/hSvOcR4/kccXMQIGkhnejKqXkXzJOHMFGqBOWVThs4OSnAPhPlXVTAZ89sK00C+JZImYrqOyoSoaq52p2pQOe09OrIBdeGVSJ3GVFt+fIB0NTBPXG2xjZr0v2EYrNl1tGWcCqktWEEjKt08pV4D68Db2jQhf4Q7YzT1km2mcQKBgQD2fpPFE2Mb2uDrturbaFkLp6B7odIIwdDE1N50zPHk1qK+49WcMQHT0OFSZauUvdyqPmkgOI2A3cjf3Wdk4Tl2K3SeeVLdErZfqWRzDML3BPEjH5x531hR4gvpWMcN0GkmGKiWLRkCYMHcydO5Ibbz+WlYnRFCSgycVcumSOnNCQKBgQDPpqgQgrwIQEYAISPU/I/atwmV7EtbSBqw6EF3+vypOJhB0GqwUJsBa7A/IQSLkfnlwIquHdgrUR1FX1La6HM4Om8JMlSBZw96dlOOcPVtEZS7QPhQDV1c/dFfVAncD13oI5DBYObZ4dYY1beRo8MNwWvaCpC/uWPxiovk/2i9BwKBgQDQuKHb8OytO4vVTNBV9WfhTJHB3maBb8ydvzqXYKs7gNvSFA5e8ciAWZFSOjEuBA8EQVC3Lev0QNjFZy8T5vrHK0jWoBkghaXUHxWlrhqxHIgrm6reL9cTjvtTHg9/jQhcb+jhMVLKBrBhiq0zSG8o6/reRDHHFfjTsHp/VaJUMQKBgQDPCHy8qXxMRbkFXAVbz8yl5qT6A7RGeKeUBp1vwKC1H6Y+yEv3KwbA7du1tXfQqGSd+9DJNRxYY/FpP1dexzBJuYkHhFTZCCZYlS1N8bXhXwwJfweU2R5jHvXns+R4siGQ2BT1mWXRiudpr3vtC3foeRbNOIeFgJPzOY2tbjHBdQKBgQCGy+oclVfmtLdQ1w32kIUFSZmfsE0UWUIk5cTFfwZv46r5IDyCW32G+ud1Yj8nTl88FijmqrvDeYW6Jucu5/e09pv3VfOqdPYXWFB8O8VtcEopiesiQ35/0iduZf2CXTH+jSRzrJG1nw57dAZmvTigcw1EdfTOgesO691cG/I+LQ==
