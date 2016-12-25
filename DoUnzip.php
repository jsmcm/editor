<?php
session_start();

$DocumentRoot = $_SERVER["DOCUMENT_ROOT"];
$DocumentRoot = substr($DocumentRoot, 6);
$DocumentRoot = "/home/".substr($DocumentRoot, 0, strpos($DocumentRoot, "/"))."/public_html/";

$URL = $_SERVER["SERVER_NAME"];

if(isset($_POST["Path"]))
{
        $Path = $_POST["Path"];
}
else
{
        $Path = $DocumentRoot;
}

if(substr($Path, strlen($Path) - 1) != "/")
{
        $Path = $Path."/";
}

if(isset($_POST["FilesAndFolders"]))
{
        $ZipFile = $_POST["FilesAndFolders"];
}
else
{
        header("location: index.php?Path=".$Path."&Error, no zip file specified");
	exit();
}

//print "Unzipping ".$ZipFile." -> to ".$Path;
//exit();

	require_once($_SERVER["DOCUMENT_ROOT"]."/includes/functions.inc");

	
	$zip = new ZipArchive;

	if($zip->open($ZipFile))
	{
		for($i=0; $i<$zip->numFiles; $i++)
		{
			$zip->getNameIndex($i);
		}

		$zip->extractTo($Path);
		$zip->close();

	} 
	else 
	{
		header("location: index.php?Notes=Error reading zip-archive!");
	}

	chmod_R($Path, 0755, 0755);

	header("Location: index.php?Path=".$Path);
?>
