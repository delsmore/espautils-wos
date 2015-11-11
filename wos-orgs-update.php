<?php

$file_handle = fopen("files/wos-orgs.csv", "r");


//include 'conn-local.php';
include 'conn.php';

try{
   $dbh = new PDO("sqlsrv:Server=$server;Database=EDINAImports", $username, $password);
   
   $dbh->exec("DROP TABLE wosPubOrganisations");
   
    $dbh->exec("CREATE TABLE wosPubOrganisations ( id smallint IDENTITY(1,1)PRIMARY KEY , OutcomeID VARCHAR(100), DOI VARCHAR(100), Organisation VARCHAR(255), SequenceNo int, Address VARCHAR(255), Country VARCHAR(100))");

//echo 'table created';

while (($line_of_data = fgetcsv($file_handle, 1000, ",")) !== FALSE) {
	$doi = $line_of_data[0];
	if ($doi !==''){ 

     $dbh->exec("INSERT into wosPubOrganisations(DOI,Organisation, SequenceNo, Address,Country) values('$line_of_data[0]','$line_of_data[1]','$line_of_data[2]','$line_of_data[3]','$line_of_data[4]')");

	}
   }
  $dbh->exec("UPDATE wosPubOrganisations  set 
OutcomeID = EdinaWOKData.OutcomeID 
FROM EdinaWOKData, wosPubOrganisations 
WHERE EdinaWOKData.DOI = wosPubOrganisations.DOI
");
 
 
// $dbh->exec("DROP TABLE wosPubOrgs_temp");

 //   echo 'Citations and WOK IDs written to Database';

$dbh = null;
}catch(PDOException $e){
   echo 'Failed to connect to database: ' . $e->getMessage() . "\n";
   exit;
}
?>