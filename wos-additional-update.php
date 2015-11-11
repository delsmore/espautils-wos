<?php



$file_handle = fopen("files/wos-additional.csv", "r");

//include 'conn-local.php';
include 'conn.php';

try{
   $dbh = new PDO("sqlsrv:Server=$server;Database=EDINAImports", $username, $password);
   
   $dbh->exec("DROP TABLE wosPubAdditional_temp");
   
    $dbh->exec("CREATE TABLE wosPubAdditional_temp ( id smallint IDENTITY(1,1)PRIMARY KEY , DOI VARCHAR(100), Ack VARCHAR(MAX), Abstract VARCHAR(MAX), Funders VARCHAR(MAX), Keywords VARCHAR(MAX))");

//echo 'table created';

while (($line_of_data = fgetcsv($file_handle, 1000, ",")) !== FALSE) {
	$doi = $line_of_data[0];
	if ($doi !==''){ 

     $dbh->exec("INSERT into wosPubAdditional_temp(DOI,Ack, Abstract, Funders,Keywords) values('$line_of_data[0]','$line_of_data[1]','$line_of_data[2]','$line_of_data[3]','$line_of_data[4]')");

	}
   }
   $dbh->exec("UPDATE EdinaWOKData  set 
WOKAcknowledgement = wosPubAdditional_temp.Ack, 
WOKAbstract = wosPubAdditional_temp.Abstract,
WOKFunders = wosPubAdditional_temp.Funders,
WOKKeywords = wosPubAdditional_temp.Keywords
FROM wosPubAdditional_temp, EdinaWOKData
WHERE wosPubAdditional_temp.DOI = EdinaWOKData.DOI
");
 
 
// $dbh->exec("DROP TABLE wosPubAdditional_temp");


 //   echo 'Citations and WOK IDs written to Database';

$dbh = null;
}catch(PDOException $e){
   echo 'Failed to connect to database: ' . $e->getMessage() . "\n";
   exit;
}
?>