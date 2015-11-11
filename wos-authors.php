<?php
$my_csv_file = 'files/wos-authors.csv';


if (file_exists($my_csv_file)) {
	
	rename($my_csv_file, "files/wos-authors-" .date('Y-m-d-His'). ".csv");
}
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
    foreach ($pub->REC->static_data->fullrecord_metadata->addresses->address_name as $add) {
		  if (isset($add->names->name)) {
			   foreach ($add->names->name as $name) {
	
	  
	 $line .= '"' . $doi .'", "' . $name->display_name . '", "' . $name['seq_no'] . '", "' . $name['addr_no'] . '", "'. $name['role'] . '", "' .$add->address_spec->organizations->organization[0] . '", "' . $add->address_spec->full_address . '", "' . $add->address_spec->country . '"' . PHP_EOL;
	
	$i++;  }
} }
$lines = $line;	 
//echo $lines;
 
fwrite($handle, $lines);


}

	
print $i . ' lines written to ' . $my_csv_file;
?>