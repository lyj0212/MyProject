<div class="lacation_mapWrap">
	<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3212.277474930255!2d127.37803213629314!3d36.37827633189509!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x35654a28faf8150b%3A0x83b591b85c4e2034!2z64yA7KCE66y47ZmU7IKw7JeF7KeE7Z2l7JuQ!5e0!3m2!1sko!2skr!4v1481250045699" width="100%" height="100%" frameborder="0" allowfullscreen title="대전정보문화산업진흥원 지도"></iframe>
</div>

// 네이버 지도 예제는 2개의 프로그램으로 구성되어 있습니다. (지도표시, 주소좌표변환)
// 네이버 지도 표시 - web
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>간단한 지도 표시하기</title>
	<script type="text/javascript" src="https://openapi.map.naver.com/openapi/v3/maps.js?clientId=4vwO3QhQHBYqdealrSiI"></script>
</head>
<body>
<div id="map" style="width:100%;height:400px;"></div>
<script>
	var map = new naver.maps.Map('map', {center: new naver.maps.LatLng(37.3595704, 127.105399)});
</script>
</body>
</html>

// 네이버 지도 Open API 예제 - 주소좌표변환
<?php
$client_id = "YOUR_CLIENT_ID";
$client_secret = "YOUR_CLIENT_SECRET";
$encText = urlencode("불정로 6");
$url = "https://openapi.naver.com/v1/map/geocode?query=".$encText; // json
// $url = "https://openapi.naver.com/v1/map/geocode.xml?query=".$encText; // xml

$is_post = false;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, $is_post);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$headers = array();
$headers[] = "X-Naver-Client-Id: ".$client_id;
$headers[] = "X-Naver-Client-Secret: ".$client_secret;
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$response = curl_exec ($ch);
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "status_code:".$status_code."<br>";
curl_close ($ch);
if($status_code == 200) {
    echo $response;
} else {
    echo "Error 내용:".$response;
}
?>


<table cellpadding="0" cellspacing="0" width="462"> <tr> 
		<td style="border:1px solid #cecece;">
			<a href="http://map.naver.com/index.nhn?menu=route&lng=0a5594e7d38a45af471783870fe95fe9&mapMode=0&lat=27952e36ade271d2c775b4ae84bfa5f8&dlevel=9&enc=b64" target="_blank">
				<img src="http://prt.map.naver.com/mashupmap/print?key=p1502958889255_-1861844338" width="460" height="340" alt="지도 크게 보기" title="지도 크게 보기" border="0" style="vertical-align:top;"/>
			</a>
		
		</td> </tr> <tr> <td> <table cellpadding="0" cellspacing="0" width="100%"> <tr> <td height="30" bgcolor="#f9f9f9" align="left" style="padding-left:9px; border-left:1px solid #cecece; border-bottom:1px solid #cecece;"> 
						<span style="font-family: tahoma; font-size: 11px; color:#666;">2017.8.17</span>&nbsp;<span style="font-size: 11px; color:#e5e5e5;">|</span>&nbsp;<a style="font-family: dotum,sans-serif; font-size: 11px; color:#666; text-decoration: none; letter-spacing: -1px;" href="http://map.naver.com/index.nhn?menu=route&lng=0a5594e7d38a45af471783870fe95fe9&mapMode=0&lat=27952e36ade271d2c775b4ae84bfa5f8&dlevel=9&enc=b64" target="_blank">지도 크게 보기</a> </td> <td width="98" bgcolor="#f9f9f9" align="right" style="text-align:right; padding-right:9px; border-right:1px solid #cecece; border-bottom:1px solid #cecece;"> <span style="float:right;"><span style="font-size:9px; font-family:Verdana, sans-serif; color:#444;">&copy;&nbsp;</span>&nbsp;<a style="font-family:tahoma; font-size:9px; font-weight:bold; color:#2db400; text-decoration:none;" href="http://www.nhncorp.com" target="_blank">NAVER Corp.</a></span> </td> </tr> </table> </td> </tr> </table>

