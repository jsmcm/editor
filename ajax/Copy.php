<?php

include("./includes/functions.inc");

function recurse_copy($src,$dst) 
{
	$dir = opendir($src); 
    	
	$LastSource = "";	
	
	if(! file_exists($dst))
	{
		@mkdir($dst);
		chmod($dst, 0755); 
    	}
	else
	{
		$LastSource = substr($src, strrpos($src, "/") + 1);
		//print $LastSource."<p>";
		$dst = $dst."/".$LastSource;
		//print $dst."<p>";	

		if(file_exists($dst))
		{
			return false;
		}
		
		@mkdir($dst);
		chmod($dst, 0755); 
		//exit();
	}

	while(false !== ( $file = readdir($dir)) ) 
	{ 
        	if (( $file != '.' ) && ( $file != '..' )) 
		{ 
            		if ( is_dir($src . '/' . $file) ) 
			{ 
                		recurse_copy($src . '/' . $file,$dst . '/' . $file); 
           		} 
            		else 
			{ 
                		copy($src . '/' . $file,$dst . '/' . $file); 
				chmod($dst."/".$file, 0755); 
            		} 
        	} 
    	} 
    
	closedir($dir); 

	return true;
} 



if(isset($_GET["NewPath"]))
{
	$OldFile = $_GET["OldFile"];
	$NewFile = $_GET["NewPath"];

	//print "OldFile: '".$OldFile."'<br>";
	//print "NewFile: '".$NewFile."'<br>";

	$Copied = "copied";
	$Copying = "copying";

	$DeleteSource = 0;
	if(isset($_REQUEST["DeleteSource"]))
	{
		$DeleteSource = $_REQUEST["DeleteSource"];
	
		if($DeleteSource == 1)
		{
			$Copied = "moved";
			$Copying = "moving";
		}
	}

	//print "Delete Source: ".$DeleteSource."<p>";
	//exit();

	if( ($OldFile != "") && ($NewFile != "") )
	{
		if(substr($OldFile, 0, 4) == "file")
		{
			//print "FILE<p>";
			$OldFile = substr($OldFile, 4);

			$x = strrpos($OldFile, "/");
			//print "x = ".$x."<br>";
		
			$Path = substr($OldFile, 0, $x + 1);


			//print "OldFile: '".$OldFile."'<br>";
			//print "NewFile: '".$NewFile."'<br>";
	

			if(file_exists($NewFile))
			{
				if(is_dir($NewFile))
				{

					$FileNamePart = substr($OldFile, strrpos($OldFile, "/") + 1);
					if(substr($NewFile, strlen($NewFile) - 1, 1) == "/")
					{
						$NewFile = $NewFile.$FileNamePart;
					}
					else
					{
						$NewFile = $NewFile."/".$FileNamePart;
					}
				}

			}
	
			//print "OldFile: '".$OldFile."'<br>";
			//print "NewFile: '".$NewFile."'<br>";
			//exit();

			if(! file_exists($NewFile))
			{
				if(copy($OldFile, $NewFile))
				{
					chmod($NewFile, 0755);

					if($DeleteSource == 1)
					{
						unlink($OldFile);
					}

					$Message = "";
				}
				else
				{
					$Message = "File could not be ".$Copied;
				}
			}
			else
			{
				$Message = "Error, that name already exists!";	
			}	

			//print $Message;
			//exit();
		}
		else if(substr($OldFile, 0, 9) == "directory")
		{
			//print "FOLDER<p>";
			$OldFile = substr($OldFile, 9);

			$x = strrpos($OldFile, "/");
			//print "x = ".$x."<br>";
		
			$Path = substr($OldFile, 0, $x + 1);

			//print "OldFile: '".$OldFile."'<br>";
			//print "NewFile: '".$NewFile."'<br>";
		

			if($OldFile == $NewFile)
			{
				$Message = "Error, source and destination are the same";
			}
			else
			{
				if( (file_exists($NewFile)) && (!is_dir($NewFile)) )
				{
					$Message = "Error, that name already exists!";	
				}	
				else
				{
					if(recurse_copy($OldFile, $NewFile))
					{
						if($DeleteSource == 1)
						{
							DeleteDirectory($OldFile);
						}

						$Message = "";
					}
					else
					{
						$Message = "Folder could not be ".$Copied." (most likely due to an existing file or folder with the same name)";
					}
				}
			}
		
			//print "Message: ".$Message;
			//exit();
		}
	
	
	}
	else
	{
		$Message = "There was a problem ".$Copying." this file / folder!";
	}
}
else
{
	$Message = "No file or folder selected!";
}

print $Message;

?>
