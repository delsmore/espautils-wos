<?php
$my_csv_file = 'files/subjects.csv';

function escapos($str) {
$str_esc = str_replace("'","''",$str);
$str_esc2 = str_replace('"','""',$str_esc);
return $str_esc2;
}

$handle = fopen($my_csv_file, 'w') or die('Cannot open file:  '.$my_csv_file); //implicitly creates file

$i = 0;
$line = '';
 foreach (glob("files/publications/*") as $filename) {
//    echo $filename;
	
	$pub = simplexml_load_file($filename);
	
	$id = $pub->REC->UID;



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
    foreach ($pub->REC->static_data->fullrecord_metadata->category_info->subjects->subject as $subject) {
	//	if ($subject['jcr_quartile'] == 'Q1' || $subject['jcr_quartile'] == 'Q2' || $subject['jcr_quartile'] == 'Q3' || $subject['jcr_quartile'] == 'Q4'){
	if ($subject['ascatype'] == 'traditional'){
		echo $subject . '<br>';

 $line .= '"' . $doi .'", "' . $subject . '"' . PHP_EOL;
		// $line .= '"' . $doi .'", "' .  '", "' .  '", "'  . $funders . '", "' . '"' . PHP_EOL;
	$i++;  
		}
}}
$lines = $line;	 
//echo $lines;
 
fwrite($handle, utf8_encode($lines));

//print $lines;




	
print $i . ' lines written to ' . $my_csv_file;
?>