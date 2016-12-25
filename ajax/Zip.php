<?php

$Path = "";

if(isset($_GET["Path"]))
{
	if(trim($_GET["Path"]) != "")
	{
		$Path = "&Path=".$_GET["Path"];
	}
}

function Zip($source, &$zip)
{
	if(trim($source) == "")
	{
		return;
	}

    $source = str_replace('\\', '/', realpath($source));

	//print "source = '".$source."'<br>";
	//print "realpath(source) = '".realpath($source)."'<br>";

    if (is_dir($source) === true)
    {

	$BasePath = substr($source, strrpos($source, '/') + 1)."/";
	//print "Base: ".$BasePath."<br>";
	$zip->addEmptyDir($BasePath);

        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

        foreach ($files as $file)
        {
            $file = str_replace('\\', '/', $file);

            // Ignore "." and ".." folders
            if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
                continue;

            $file = realpath($file);

            if (is_dir($file) === true)
            {
		//print "addEmptyDir(".str_replace($source . '/', '', $file . '/').")<br>";
                $zip->addEmptyDir($BasePath.str_replace($source . '/', '', $file . '/'));
            }
            else if (is_file($file) === true)
            {
                //print "addFromString(".str_replace($source . '/', '', $file).", ".file_get_contents($file).");<br>";
                $zip->addFromString($BasePath.str_replace($source . '/', '', $file), file_get_contents($file));
            }
        }
    }
    else if (is_file($source) === true)
    {
        $zip->addFromString(basename($source), file_get_contents($source));
    }

}


if(isset($_GET["FilesAndFolders"]))
{
        $FilesAndFolder = array();
        $FilesAndFolders = json_decode($_GET["FilesAndFolders"], true);

        if( count($FilesAndFolders["Files"]) > 0)
        {
                $destination = $FilesAndFolders["Files"][0].".zip";
		
        }
	else if (count($FilesAndFolders["Folders"]) > 0)
        {
                $destination = $FilesAndFolders["Folders"][0].".zip";
        }
	else
	{
		print "Error creating zip file (no files selected)!";
		exit();
	}
}
else
{
	print "Error creating zip file (no files selected)!";
	exit();
}

    
if (!extension_loaded('zip') ) 
{
	print "Error creating zip file (zip extension not found)!";
	exit();
}

$zip = new ZipArchive();
if (!$zip->open($destination, ZIPARCHIVE::CREATE)) 
{
	print "Error creating zip file (cant create: ".$destination.")!";
	exit();
}


if(isset($_GET["FilesAndFolders"]))
{
        $FilesAndFolder = array();
        $FilesAndFolders = json_decode($_GET["FilesAndFolders"], true);

        for($x = 0; $x < count($FilesAndFolders["Files"]); $x++)
        {
		if(trim($FilesAndFolders["Files"][$x]) != "")
		{
                	//print "Zip File: ".$FilesAndFolders["Files"][$x]."<br>";
	                Zip($FilesAndFolders["Files"][$x], $zip);
		}
        }

        for($x = 0; $x < count($FilesAndFolders["Folders"]); $x++)
        {
		if(trim($FilesAndFolders["Folders"][$x]) != "")
		{
              		//print "Zip Directory: ".$FilesAndFolders["Folders"][$x]."<br>";
	                Zip($FilesAndFolders["Folders"][$x], $zip);	
		}
        }
}



$zip->close();

chmod($destination, 0755);

print "";

?>
