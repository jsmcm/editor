<?php
session_start();

if(isset($_GET["FilesAndFolders"]))
{
        $ZipFile = $_GET["FilesAndFolders"];
}
else
{
        print "Error, no zip file specified";
	exit();
}


	$Path = $_GET["Path"];

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
		print "Error reading zip-archive!";
	}

	chmod_R($Path, 0755, 0755);

	print "";

?>
