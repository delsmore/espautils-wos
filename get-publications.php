<?php
//session_start();
//$token = $_SESSION['token'];
$token = file_get_contents("token.txt");

//print_r($_POST);
//print $token;

$file_handle = fopen("../wok/files/wok.csv", "r");
$i=0;

while (!feof($file_handle) ) {

$line_of_text = fgetcsv($file_handle, 1024);
if ($i>0) {
$ref =	$line_of_text[0];
//print  $ref . '<br>';
 $myref = str_replace('/','-',$ref);
 //print $myref . '<br>';


//create request 
$wokrequest = '<soapenv:Envelope	xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
				xmlns:wok="http://woksearch.v3.wokmws.thomsonreuters.com">
   <soapenv:Header/>
   <soapenv:Body>
      <wok:search>
         <queryParameters>
            <databaseId>WOS</databaseId>
            <userQuery>DO=' . $ref . '</userQuery>
            <queryLanguage>en</queryLanguage>
         </queryParameters>
         <retrieveParameters>
            <firstRecord>1</firstRecord>
            <count>100</count>
         </retrieveParameters>
      </wok:search>
   </soapenv:Body>
</soapenv:Envelope>';

 $header = array(
//"POST http://search.webofknowledge.com/esti/wokmws/ws/WOKMWSAuthenticate HTTP/1.1",   
"Accept-Encoding: gzip,deflate",
"Content-Type: application/xml",
"Cookie: SID=" . $token,
"Content-Length:" . strlen($wokrequest),
"Host: search.webofknowledge.com",
"Connection: Keep-Alive",
"User-Agent: Apache-HttpClient/4.1.1 (java 1.5)",
  );
//var_dump($header);
  $soap_do = curl_init();
  curl_setopt($soap_do, CURLOPT_URL, "http://search.webofknowledge.com/esti/wokmws/ws/WokSearch" );
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
  $result=strip_tags($result);
  $result = html_entity_decode($result);
  $result = strstr($result, '<records');
  $proj = str_replace($ref,'/','-');
 $my_file = 'files/publications/pub-'. $myref . '.xml';
$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file); //implicitly creates file

fwrite($handle,  $result ); 

  
}$i++;
// sleep for 10 seconds
sleep(1);

// wake up !
//echo date('h:i:s') . "\n";
}

fclose($file_handle);



echo 'Done';
?>

