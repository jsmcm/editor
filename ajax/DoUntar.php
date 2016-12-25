<?php
session_start();

$DocumentRoot = $_SERVER["DOCUMENT_ROOT"];
$DocumentRoot = substr($DocumentRoot, 6);
$DocumentRoot = "/home/".substr($DocumentRoot, 0, strpos($DocumentRoot, "/"))."/public_html/";

$URL = $_SERVER["SERVER_NAME"];

if(isset($_GET["Path"]))
{
        $Path = $_GET["Path"];
}
else
{
        $Path = $DocumentRoot;
}

if(substr($Path, strlen($Path) - 1) != "/")
{
        $Path = $Path."/";
}

$DotFound = 0;

if(isset($_GET["FilesAndFolders"]))
{
        $TarGzFile = $_GET["FilesAndFolders"];

	$Directory = substr($TarGzFile, 0, strrpos($TarGzFile, "/"));	
        
}
else
{
        print "Error, no tar file specified";
	exit();
}

	

	//print "Tar.gz: ".$TarGzFile."<br>";
	//print "Dir: ".$Directory."<br>";
	shell_exec("tar xvfz ".$TarGzFile." -C ".$Directory);

	print "";
?>
