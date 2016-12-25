<?php
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/includes/functions.inc");

if(CheckLogin() != true)
{
        print "Sorry, your login was invalid or your session expired!";
        exit();
}

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
		
	move_uploaded_file($_FILES['UploadedFile']['tmp_name'], $UploadedFile=$Path.$_FILES['UploadedFile']['name']);
	chmod($UploadedFile, 0755);
	
	//print $Reply;
	header("location: index.php?Path=".$Path."&Notes=File Uploaded");
?>
