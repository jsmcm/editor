<?php
session_start();

require_once("./config.inc.php");

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

$DotFound = 0;

if(isset($_POST["FilesAndFolders"]))
{
        $TarGzFile = $_POST["FilesAndFolders"];

	$Directory = substr($TarGzFile, 0, strrpos($TarGzFile, "/"));	
        
}
else
{
        header("location: index.php?Path=".$Path."&Error, no tar file specified");
	exit();
}

	

	//print "Tar.gz: ".$TarGzFile."<br>";
	//print "Dir: ".$Directory."<br>";
	shell_exec("tar xvfz ".$TarGzFile." -C ".$Directory);

	header("Location: index.php?Path=".$Path);
?>

