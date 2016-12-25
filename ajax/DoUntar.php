<?php
session_start();
require_once("../config.inc.php");

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
