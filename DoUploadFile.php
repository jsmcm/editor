<?php
session_start();
require_once("./includes/functions.inc.php");
require_once("./config.inc.php");

if(CheckLogin($EmailAddress) != true)
{
        print "Sorry, your login was invalid or your session expired!";
        exit();
}

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
