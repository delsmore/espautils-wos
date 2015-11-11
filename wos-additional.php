<?php
$my_csv_file = 'files/wos-additional.csv';

function escapos($str) {
$str_esc = str_replace("'","''",$str);
$str_esc2 = str_replace('"','""',$str_esc);
return $str_esc2;
}

/*
if (file_exists($my_csv_file)) {
	
	rename($my_csv_file, "files/wos-additional-" .date('Y-m-d-His'). ".csv");
}
*/
$handle = fopen($my_csv_file, 'a') or die('Cannot open file:  '.$my_csv_file); //implicitly creates file

$i = 0;
 foreach (glob("files/publications/*") as $filename) {
//    echo $filename;
	
	$pub = simplexml_load_file($filename);
	
	$id = $pub->REC->UID;

$pubtype =  $pub->REC->static_data->summary->pub_info["pubtype"];

    foreach($pub->REC->static_data->summary->titles->title as $pubtitle) {
	 if ($pubtitle["type"] == 'source') {
		 $source = $pubtitle;
	  }
	 if ($pubtitle["type"] == 'item') {
		 $title = $pubtitle;
		  }
	 
 foreach($pub->REC->dynamic_data->cluster_related->identifiers->identifier as $identifier) {
 if ($identifier['type'] == 'doi') {
	 $doi = $identifier['value'] ;
 }}
 } 
 $line = '';
    foreach ($pub->REC->static_data->fullrecord_metadata as $metadata) {
		$funders = '';
		 if (isset($metadata->fund_ack->grants->grant)) {
		foreach($metadata->fund_ack->grants->grant as $grant) {
			$funders .=  $grant->grant_agency . ';';
		}}
		$keywords = '';
		 if (isset($pub->REC->static_data->item->keywords_plus)) {
		foreach($pub->REC->static_data->item->keywords_plus->keyword as $kword) {
			$keywords .=  $kword . ';';
		}}
 $line .= '"' . $doi .'", "' . escapos($metadata->fund_ack->fund_text->p) . '", "' . escapos($metadata->abstracts->abstract->abstract_text->p) . '", "' . escapos($funders) . '", "' . escapos($keywords) . '"' . PHP_EOL;
		// $line .= '"' . $doi .'", "' .  '", "' .  '", "'  . $funders . '", "' . '"' . PHP_EOL;
	$i++;  
}
$lines = $line;	 
//echo $lines;
 
fwrite($handle, utf8_encode($lines));

//print $lines;
}



	
print $i . ' lines written to ' . $my_csv_file;
?>