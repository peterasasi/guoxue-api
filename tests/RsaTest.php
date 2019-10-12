<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 2019-05-29
 * Time: 17:51
 */

namespace byTest\component\zmf_pay;

use by\component\encrypt\rsa\Rsa;
use PHPUnit\Framework\TestCase;


class RsaTest extends TestCase {

    public function testDecrypt() {
        $encrypt = 'a=123';
        //'{"a":"123"}';
        var_dump($encrypt);
        $encrypt = '{"client_id":"byy323","money":"10000","order_no":"23324324","pay_code":"abadsf","trade_no":"trade_no"}';
        $rsa = Rsa::generateRsaKeys('sha256');
//        var_dump($rsa);
        $publicKey = '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAoZC02j8JOimrdP0RSpKN
EZzyb7Vw5YI28jTgWa55gi2XgU8PtD48/nGWwHVviVhR/8x+hWzzeemmtkkaO7ep
LulUjwmVyVMco4PhJBaYsLJnVdD+O2i2r6VfX7FOV9dmO5ASjyz/5zOH92HjIiKm
ayEN7VbHO4e8RZolZOjXcCm8W9ycJMfwsKFjB0IPQ2n0gOUfqUwYeGYCVsBHm2SL
mYBGv0O/F5hqWIqiPvyAiadXJ1f2166hOtCQrOvgTTUVYcdLkWsdb/6HIfbuzYcw
aYrzbvFH3kD2dL9jKXIX1km/42MemwcK32YDoJFtUGtRn8t6srygJPBNtAfOahmo
lQIDAQAB
-----END PUBLIC KEY-----';


        $privateKey = '-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQChkLTaPwk6Kat0
/RFKko0RnPJvtXDlgjbyNOBZrnmCLZeBTw+0Pjz+cZbAdW+JWFH/zH6FbPN56aa2
SRo7t6ku6VSPCZXJUxyjg+EkFpiwsmdV0P47aLavpV9fsU5X12Y7kBKPLP/nM4f3
YeMiIqZrIQ3tVsc7h7xFmiVk6NdwKbxb3Jwkx/CwoWMHQg9DafSA5R+pTBh4ZgJW
wEebZIuZgEa/Q78XmGpYiqI+/ICJp1cnV/bXrqE60JCs6+BNNRVhx0uRax1v/och
9u7NhzBpivNu8UfeQPZ0v2MpchfWSb/jYx6bBwrfZgOgkW1Qa1Gfy3qyvKAk8E20
B85qGaiVAgMBAAECggEASx+nCCfxWWsQv1gAl0UndP4eOFUMhpsSg3KUsv6Onzol
zUl+ytd5svIMH7c7QoifsCpc1ht/5rr++plpKkVO1HJIEC5ryG3tzhY/NHGRU+/Q
XrXL0MHy2B80BqHCBNQyRBXMm2hYIR/z49JPM4sbCIsZF7eW8hY+M7rmElK2Af+Y
3yp6o8lz/RaPJXGvsdJX7lR2MuKHrORJq+Ws6dS/Hhw5M6XWxVdkbir0JOEx5kda
1WQBo2NrRdfZvlCFLNhyJRpPeUW2jZVz3CCWQ9jGI8KH7PiKD1fGGI5xIyIUPubT
UQRWT9lFCeFMnWhK3B+xOgfH3+jEH0MhjjiW1udzjQKBgQDOtbx3ecBoVYIo5Q3O
CP4sZNSYEl4Cyk84p/KfIls/PQ2bV4ke+dhhNkwWWnhrdLROMJTPkBPZ5o0BId+T
sWUesNmS/6961TZDB7O0iWlJYxXR0PUyyIXrVxFdfoLmkIDz/ceQcNNyzRLDMB0x
HJtHthwsgdkFhgH2R1vXfusQKwKBgQDIFzCnEHX7G39dYjhg4wjlohBOiUoNhOzN
zDZAKckIMG7PmOYlmsu9cg7PXrILoHo4lQ2TmxEdw3tr8Ff4YwJAWpSdzJaDXh6W
+O68ldTzBo1/GzZzj+oX0t2MabmTKw+BPVdHU7sIsUinb7/IMxXlwf2V65fequ8K
UXoUYF0KPwKBgQDJOMa3c3Dm395t2tP+bminZJxDURXtF23ZFDytxq8Wt2MohiT4
X3TQ5R9aX0V8LUZcGxSxnWqFotY+dPAFARoTr0qNu5LiOu2wgK3iICW7B9a0wfn/
Lo6XEtHXtpDMy3yMh84eAIcF6TXLhmnUZimOvGtetCREKTh9uudftrAyswKBgDiE
DFLz3E8r6iKnToNXPTkXOIHmV0tQQ06xopUm+Ehe3b23UzJF8cw1vicmeSW9kygO
OcJ6ZzA/+cl4HnDjDky9CQM1OUIrwHomH9CWhYqLTEYdmpizioxmG/vAFRGTdRKt
D8zuwoJnCL16W/IhM8ssqwLAg6n6IACLGO9OdJkjAoGAHSwbgbkr5hHtkJEK5/ZJ
1HN50OcbMc7dbzs/3VzAPVzlXWM+vMOrsJuDv3yR8MdHL/aCD4/kON87+jMrCL6S
Pt73YOO91nHhrL/fdDXfiRZZ8JWFDsQm0e+wTRC2OJeyUEo10qKhxPnraiCJuKdy
fiRn/etA8ZOjAw1zwgoeJw8=
-----END PRIVATE KEY-----';

        $userPublicKey = Rsa::formatPublicText('MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAxevxFua8o/ph2vIykAIUxAVLMQb0KOWPeXGYJBTcCoC825skkKmdrQdoaxR7MqLeeTT50dvV6i/8ZlmiKlFT6728SmSayj/BmEnlVaa29adyNFJBrcywcxqEtIVEwkO1An6D2loI54xc7qWdUTuyEB9JmsH5U1UUV3Qpu8fJxZtuwJoM7GmHZDm9gVx2kTwq9rM58FktzvdnA2eqnE9pQsDlN8/Cg0bUKXXi5+mFfOUCaYZSjPVYm56xO24JPGCzA3+YDVh5T1lz9YXh8sXKhiAebJp5VU6EzJDsjU0nstFwmkDVeX+cfxE+atEs5AJOKcsxDomWMiOTOqIsXyJxDwIDAQAB');
        var_dump($userPublicKey);
        $userPrivateKey = '-----BEGIN RSA PRIVATE KEY-----
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

        $sign = Rsa::sign($encrypt, $privateKey);
        $sign = 'J3nLsgcBUJvQ0RjvvXgi5FIoQrBSvsMWenqI2Rh0ggARV4kSPrY/5/j+oO5fVlVL8G0TbI75cGra40uXBv1KeqrxSc5MWU4Nmapwi8XBDjzJW0byurrCZbj0R+4u6SvAkMpqQLBsnYDndXd5GBX4h69EuTfoyrlg6O6dNItdBAgmE2tx71q0g1hrZjutPJtt9gEhyM8IekKaOpnuYru7xUTvTU0sy0dpDDyZj9nmRcTQ+oXxGcTem/VwHhfOLxWY/b1WXOh23VvtTss3W25Z7F9EhrFGSn05531x2BOM5HL/IznPd5ggq5eyCi2AwzB2m9Zw6aUTSKPsfNR1jAVDNA==';
        var_dump(($sign));
        $encrypt = json_decode($encrypt, JSON_OBJECT_AS_ARRAY);
        $encrypt['sign'] = $sign;
        $encrypt = json_encode($encrypt, JSON_UNESCAPED_UNICODE);

        $content = Rsa::encryptChunk($encrypt, $userPublicKey);

        $body['content'] = $content;
        $body['client_id'] = 'byy323';
        $body['alg'] = 'rsa_v2';
        var_dump($body);

        var_dump(json_decode(Rsa::decryptChunk($content, $userPrivateKey), JSON_OBJECT_AS_ARRAY));

//        $verify = Rsa::verifySign($encrypt, $sign, $publicKey);
//        var_dump($verify);
    }
}
