<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?PHP

$file_handle = fopen("files/wok.csv.csv", "r");

while (!feof($file_handle) ) {

$line_of_text = fgetcsv($file_handle, 1024);

print $line_of_text[0] . $line_of_text[1]. $line_of_text[2] . "<BR>";

}

fclose($file_handle);

?>
</body>
</html>