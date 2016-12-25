<?php

require_once("../includes/functions.inc.php");

if(isset($_GET["FilesAndFolders"]))
{
	$FilesAndFolder = array();
	$FilesAndFolders = json_decode($_GET["FilesAndFolders"], true);

	for($x = 0; $x < count($FilesAndFolders["Files"]); $x++)
	{
		//print "Delete File: ".$FilesAndFolders["Files"][$x]."<br>";
		DeleteFile($FilesAndFolders["Files"][$x]);
	}

	for($x = 0; $x < count($FilesAndFolders["Folders"]); $x++)
	{
		//print "Delete Directory: ".$FilesAndFolders["Folders"][$x]."<br>";
		DeleteDirectory($FilesAndFolders["Folders"][$x]);
	}
}

?>

deleted
