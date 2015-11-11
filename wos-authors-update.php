<?php



$file_handle = fopen("files/wos-authors.csv", "r");

//include 'conn-local.php';
include 'conn.php';

try{
   $dbh = new PDO("sqlsrv:Server=$server;Database=EDINAImports", $username, $password);
   
   $dbh->exec("DROP TABLE wosPubAuthors");
   
    $dbh->exec("CREATE TABLE wosPubAuthors ( id smallint IDENTITY(1,1)PRIMARY KEY , OutcomeID VARCHAR(100), DOI VARCHAR(100), Name VARCHAR(100), SequenceNo int, OrgSequenceNo int, Role VARCHAR(100), Organisation VARCHAR(255), Address VARCHAR(255), Country VARCHAR(100))");

//echo 'table created';

while (($line_of_data = fgetcsv($file_handle, 1000, ",")) !== FALSE) {
	$doi = $line_of_data[0];
	if ($doi !==''){ 

     $dbh->exec("INSERT into wosPubAuthors(DOI,Name, SequenceNo, OrgSequenceNo, Role, Organisation,Address,Country) values('$line_of_data[0]','$line_of_data[1]','$line_of_data[2]','$line_of_data[3]','$line_of_data[4]','$line_of_data[5]','$line_of_data[6]','$line_of_data[7]')");

	}
   }
  $dbh->exec("UPDATE wosPubAuthors  set 
OutcomeID = EdinaWOKData.OutcomeID 
FROM EdinaWOKData, wosPubAuthors 
WHERE EdinaWOKData.DOI = wosPubAuthors.DOI
");
 
 
// $dbh->exec("DROP TABLE wosPubOrgs_temp");


 //   echo 'Citations and WOK IDs written to Database';

$dbh = null;
}catch(PDOException $e){
   echo 'Failed to connect to database: ' . $e->getMessage() . "\n";
   exit;
}
?>