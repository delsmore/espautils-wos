<?php


//create request 
$wokrequest = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:auth="http://auth.cxf.wokmws.thomsonreuters.com">
   <soapenv:Header/>
   <soapenv:Body>
      <auth:closeSession/>
   </soapenv:Body>
</soapenv:Envelope>';


 $header = array(
//"POST http://search.webofknowledge.com/esti/wokmws/ws/WOKMWSAuthenticate HTTP/1.1",   
"Accept-Encoding: gzip,deflate",
"Content-Type: application/xml",
"Cookie: SID=W7hgw2dH9LTRLgmD6Xm",
"Content-Length:" . strlen($wokrequest),
"Host: search.webofknowledge.com",
"Connection: Keep-Alive",
"User-Agent: Apache-HttpClient/4.1.1 (java 1.5)",
   
  );
//print_r($header);
  $soap_do = curl_init();
  curl_setopt($soap_do, CURLOPT_URL, "http://search.webofknowledge.com/esti/wokmws/ws/WOKMWSAuthenticate" );
  curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 100);
  curl_setopt($soap_do, CURLOPT_TIMEOUT,        100);
  curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true );
  curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($soap_do, CURLOPT_POST,           true );
  curl_setopt($soap_do, CURLOPT_POSTFIELDS,     $wokrequest);
  curl_setopt($soap_do, CURLOPT_HTTPHEADER,     $header);
 

  if(curl_exec($soap_do) === false) {
    $err = 'Curl error: ' . curl_error($soap_do);
    curl_close($soap_do);
    print $err;
	
  } else {
	 $result = curl_exec($soap_do);
	
    curl_close($soap_do);
  }
print $result;


//echo $reqno . ' WoK response files written<br><br>';

?>

