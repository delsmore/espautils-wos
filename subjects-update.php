<?php

$file_handle = fopen("files/subjects.csv", "r");


include 'conn.php';
//include 'conn-local.php';

try{
   $dbh = new PDO("sqlsrv:Server=$server;Database=EDINAImports", $username, $password);
   
  $dbh->exec("DROP TABLE PubSubjects");
   
    $dbh->exec("CREATE TABLE PubSubjects ( id smallint IDENTITY(1,1)PRIMARY KEY , DOI VARCHAR(100), SubjectID int, SubjectName VARCHAR(255))");

//echo 'table created';

while (($line_of_data = fgetcsv($file_handle, 1000, ",")) !== FALSE) {
	$doi = $line_of_data[0];
	if ($doi !==''){ 

     $dbh->exec("INSERT into PubSubjects(DOI,SubjectName) values('$line_of_data[0]','$line_of_data[1]')");

	}
   }
  $dbh->exec("UPDATE PubSubjects  set 
SubjectID = Subjects.ID 
FROM Subjects, PubSubjects
WHERE Subjects.Name = PubSubjects.SubjectName
");
 
 


$dbh = null;
}catch(PDOException $e){
   echo 'Failed to connect to database: ' . $e->getMessage() . "\n";
   exit;
}
?>