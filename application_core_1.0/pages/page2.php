<?php

echo $_SERVER['HTTP_REFERER'];
echo '<br><br>';

//phpinfo();
exit();


$ch = curl_init( /*'http://www.atlasformen.ru/'*/ );

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLINFO_HEADER_OUT, 1);

curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_URL, 'http://ucl.mixmarket.biz/uni/clk.php?id=1294953531&zid=1294965347&prid=1294931457&stat_id=0&sub_id=&redir=http://www.atlasformen.ru/');
//curl_setopt($ch, CURLOPT_URL, 'http://www.google.ru/aclk?sa=l&ai=CLGMdvF9uUcTeMtCChAfQ4IC4BJzSvpQFvPfY0X7hjKidAQgAEAEoAlDF-k1ghPXuhYgeoAG0p-jVA8gBAakCtVIpiSC4hz6qBCJP0FQSvVdn4n6RzmN7Q3cdMtw11a0WxUD_S0INj19NsHTYgAe02Jcq&sig=AOD64_1goAs6NSW_EyiIwloVNOVjsU6-rg&ved=0CC8Q0Qw&adurl=http://www.stylepit.ru/men&rct=j&q=%D0%BC%D1%83%D0%B6%D1%81%D0%BA%D0%B0%D1%8F+%D0%BE%D0%B4%D0%B5%D0%B6%D0%B4%D0%B0');

/*$h = apache_request_headers();

$h['Referer'] = 'http://www.google.ru/aclk?sa=L&ai=CzaH0CEZdUbbQH-jswQOxuIBQ-tejpgOSyve1VYDQ_YoKEAEoCFDKi4er-_____8BYITd54WAHZABAsgBAakCzv3Fj9zuYD6qBCVP0LUD-Thda69dNlQAwIsOIeuXaYKH4iY3lGaOwYBQj56KSGQOuAYBgAfS58Eh&num=4&sig=AOD64_3mp0qxuneCqND34UpxqEUyM3ja5Q&ved=0CJ8BENEMOAo&adurl=ucl.mixmarket.biz/uni/clk.php%3Fid%3D1294953531%26zid%3D1294965347%26prid%3D1294931457%26stat_id%3D0%26sub_id%3D%26redir%3Dwww.atlasformen.ru/&rct=j&q=%D0%BC%D1%83%D0%B6%D1%81%D0%BA%D0%B0%D1%8F+%D0%BE%D0%B4%D0%B5%D0%B6%D0%B4%D0%B0+%D0%B8%D0%B2%D0%B0%D0%BD%D0%BE%D0%B2%D0%BE';
//var_dump( $h );

unset($h['Host']);
$ah = "\r\n\r\nHTTP/1.1 302 Found\r\n";
        foreach($h as $kk => $vv)
        {
            $ah .= "$kk: $vv\r\n";
        }
        $ah .= "\r\n";
        //echo $ah;
        
        echo $ah="\r\n".'
HTTP/1.1 302 Found
Server: nginx/1.0.12
Date: Wed, 17 Apr 2013 08:56:02 GMT
Content-Type: text/html
Connection: keep-alive
X-Powered-By: PHP/5.3.21
Set-Cookie: _CSID=6650737689; path=/; domain=.mixmarket.biz
Set-Cookie: _PSID=2991749019; expires=Tue, 07-Apr-2015 08:56:03 GMT; path=/; domain=.mixmarket.biz
Set-Cookie: _UPSID=2991749019; expires=Tue, 07-Apr-2015 08:56:03 GMT; path=/; domain=.mixmarket.biz
P3P: policyref="http://mixmarket.biz/w3c/p3p.xml", CP="NOI DEV PSA PSD IVA PVD OTP OUR OTR IND OTC"
Location: http://www.atlasformen.ru/?utm_source=Mixmarket&utm_medium=Affiliation-Free-format&utm_campaign=Free-format_Mixmarket_Spring-13
Content-Length: 0
'."\r\n";
        //var_dump($ah);
curl_setopt($ch, CURLOPT_HTTPHEADER, $ah);%D0%B8%D0%B2%D0%B0%D0%BD%D0%BE%D0%B2%D0%BE
*/
curl_setopt($ch, CURLOPT_INTERFACE, $_SERVER['REMOTE_ADDR'] );
curl_setopt($ch, CURLOPT_REFERER, 'http://www.google.ru/aclk?sa=L&ai=CzaH0CEZdUbbQH-jswQOxuIBQ-tejpgOSyve1VYDQ_YoKEAEoCFDKi4er-_____8BYITd54WAHZABAsgBAakCzv3Fj9zuYD6qBCVP0LUD-Thda69dNlQAwIsOIeuXaYKH4iY3lGaOwYBQj56KSGQOuAYBgAfS58Eh&num=4&sig=AOD64_3mp0qxuneCqND34UpxqEUyM3ja5Q&ved=0CJ8BENEMOAo&adurl=ucl.mixmarket.biz/uni/clk.php%3Fid%3D1294953531%26zid%3D1294965347%26prid%3D1294931457%26stat_id%3D0%26sub_id%3D%26redir%3Dwww.atlasformen.ru/&rct=j&q=%D0%BC%D1%83%D0%B6%D1%81%D0%BA%D0%B0%D1%8F+%D0%BE%D0%B4%D0%B5%D0%B6%D0%B4%D0%B0+');

    $response = curl_exec($ch);

$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
echo $header = substr($response, 0, $header_size);

    //header('HTTP/1.1 302 Found');
    //header('Location: http://ucl.mixmarket.biz/uni/clk.php?id=1294953531&zid=1294965347&prid=1294931457&stat_id=0&sub_id=&redir=http://www.atlasformen.ru/');

/*header('HTTP/1.1 302 Found');
header('Set-Cookie: _CSID=6650737689; path=/; domain=.mixmarket.biz');
header('Set-Cookie: _PSID=2991749019; expires=Tue, 07-Apr-2015 08:56:03 GMT; path=/; domain=.mixmarket.biz');
header('Set-Cookie: _UPSID=2991749019; expires=Tue, 07-Apr-2015 08:56:03 GMT; path=/; domain=.mixmarket.biz');
header('Location: http://www.atlasformen.ru/?utm_source=Mixmarket&utm_medium=Affiliation-Free-format&utm_campaign=Free-format_Mixmarket_Spring-13');*/


//header('Set-Cookie: _CSID__12=6650737689; path=/; domain=.mixmarket.biz');
//setcookie('_CSID__1_5', '6650737689', time()+3600, '/', '.mixmarket.biz');
//$body = substr($response, $header_size);


//$output = 
        //curl_exec($ch);
//echo curl_getinfo($ch, CURLINFO_HTTP_CODE);
//echo curl_getinfo($ch, CURLINFO_HEADER_OUT);

//var_dump( curl_getinfo($ch) );


echo $ip=$_SERVER['REMOTE_ADDR'];
        

curl_close ($ch);

exit();
?>