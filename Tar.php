<?php

$Path = "";

if(isset($_POST["Path"]))
{
	if(trim($_POST["Path"]) != "")
	{
		$Path = "&Path=".$_POST["Path"];
	}
}


if(isset($_POST["FilesAndFolders"]))
{
        $FilesAndFolder = array();
        $FilesAndFolders = json_decode($_POST["FilesAndFolders"], true);

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
		header("Location: index.php?Notes=Error creating tar.gz file (no files selected)!".$Path);
		exit();
	}
}
else
{
	header("Location: index.php?Notes=Error creating tar.gz file (no files selected)!".$Path);
	exit();
}

//print $destination."<br>";

$DestinationFolder = substr($destination, 0, strrpos($destination, "/") + 1);
//print $DestinationFolder."<br>";

//print "shell_exec(\"tar -C \"".$DestinationFolder." -cf ".$destination." -T /dev/null\");<br>";

shell_exec("tar -C ".$DestinationFolder." -cf ".$destination." -T /dev/null");

if(isset($_POST["FilesAndFolders"]))
{
        $FilesAndFolder = array();
        $FilesAndFolders = json_decode($_POST["FilesAndFolders"], true);

        for($x = 0; $x < count($FilesAndFolders["Files"]); $x++)
        {
		if(trim($FilesAndFolders["Files"][$x]) != "")
		{
                	//print "tar File: ".substr($FilesAndFolders["Files"][$x], strrpos($FilesAndFolders["Files"][$x], "/") + 1)."<br>";
			shell_exec("tar -C ".$DestinationFolder." -rf ".$destination." ".substr($FilesAndFolders["Files"][$x], strrpos($FilesAndFolders["Files"][$x], "/") + 1));
		}
        }

        for($x = 0; $x < count($FilesAndFolders["Folders"]); $x++)
        {
		if(trim($FilesAndFolders["Folders"][$x]) != "")
		{
			//print "tar Directory: ".substr($FilesAndFolders["Folders"][$x], strrpos($FilesAndFolders["Folders"][$x], "/") + 1)."<br>";
			shell_exec("tar -C ".$DestinationFolder." -rf ".$destination." ".substr($FilesAndFolders["Folders"][$x], strrpos($FilesAndFolders["Folders"][$x], "/") + 1));
		}
        }
}

//print "shell_exec(\"gzip \"".$destination.");";

shell_exec("gzip ".$destination);

chmod($destination.".gz", 0755);

//print "Done";
header("location: index.php?".$Path);
?>


