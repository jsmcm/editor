<?php
$x = "";
$JDArray = array();
$FoldersArray = array();

$fp = fopen("./test.txt", "w");

if(isset($_POST["JD"]))
{	
	fwrite($fp, "File Post: ".$_POST["JD"]."\r\n");
	$JDArray = json_decode($_POST["JD"]);
}

if(isset($_POST["Folders"]))
{
	fwrite($fp, "Folder Post: ".$_POST["Folders"]."\r\n");
	$FoldersArray = json_decode($_POST["Folders"]);
}


fwrite($fp, "ererre\r\n");

fwrite($fp, "JD SIZE: ".count($JDArray)."\r\n");
fwrite($fp, "JD: ".$JDArray[0]."\r\n");


for($x = 0; $x < count($FoldersArray); $x++)
{
	fwrite($fp, "Folder: ".$FoldersArray[$x]."\r\n");
}

fclose($fp);

?>
