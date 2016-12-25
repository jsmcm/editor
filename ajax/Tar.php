<?php

$Path = "";

if(isset($_GET["Path"]))
{
	if(trim($_GET["Path"]) != "")
	{
		$Path = "&Path=".$_GET["Path"];
	}
}


if(isset($_GET["FilesAndFolders"]))
{
        $FilesAndFolder = array();
        $FilesAndFolders = json_decode($_GET["FilesAndFolders"], true);

        if( count($FilesAndFolders["Files"]) > 0)
        {
                $destination = $FilesAndFolders["Files"][0].".tar";
		
        }
	else if (count($FilesAndFolders["Folders"]) > 0)
        {
                $destination = $FilesAndFolders["Folders"][0].".tar";
        }
	else
	{
		print "Error creating tar.gz file (no files selected)!";
		exit();
	}
}
else
{
	print "Notes=Error creating tar.gz file (no files selected)!";
	exit();
}

//print "Destination: " . $destination."\r\n";

$DestinationFolder = substr($destination, 0, strrpos($destination, "/") + 1);
//print "DestionationFolder: ".$DestinationFolder."\r\n";

//print "shell_exec(\"tar -C \"".$DestinationFolder." -cf ".$destination." -T /dev/null\");\r\n";

shell_exec("tar -C ".$DestinationFolder." -cf ".$destination." -T /dev/null");

if(isset($_GET["FilesAndFolders"]))
{
        $FilesAndFolder = array();
        $FilesAndFolders = json_decode($_GET["FilesAndFolders"], true);

        for($x = 0; $x < count($FilesAndFolders["Files"]); $x++)
        {
		if(trim($FilesAndFolders["Files"][$x]) != "")
		{
                	//print "tar File: ".substr($FilesAndFolders["Files"][$x], strrpos($FilesAndFolders["Files"][$x], "/") + 1)."\r\n";
			shell_exec("tar -C ".$DestinationFolder." -rf ".$destination." ".substr($FilesAndFolders["Files"][$x], strrpos($FilesAndFolders["Files"][$x], "/") + 1));
		}
        }

        for($x = 0; $x < count($FilesAndFolders["Folders"]); $x++)
        {
		if(trim($FilesAndFolders["Folders"][$x]) != "")
		{
			//print "tar Directory: ".substr($FilesAndFolders["Folders"][$x], strrpos($FilesAndFolders["Folders"][$x], "/") + 1)."\r\n";
			shell_exec("tar -C ".$DestinationFolder." -rf ".$destination." ".substr($FilesAndFolders["Folders"][$x], strrpos($FilesAndFolders["Folders"][$x], "/") + 1));
		}
        }
}

//print "shell_exec(\"gzip \"".$destination.");\r\n";

shell_exec("gzip ".$destination);

chmod($destination.".gz", 0755);

print "";
?>
