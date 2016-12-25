<?php

require_once("./includes/functions.inc.php");
require_once("./config.inc.php");

if(isset($_REQUEST["URL"]))
{
	$URL = $_REQUEST["URL"];
}
else
{
	$URL = $_SERVER["SERVER_NAME"];
}


if(isset($_REQUEST["Path"]))
{
	$Path = $_REQUEST["Path"];
}
else
{
	$Path = $DocumentRoot;
}
	
if(substr($Path, strlen($Path) - 1) != "/")
{
	$Path = $Path."/";
}


        if(strlen($Path) > strpos($Path, "public_html") + 12)
        {
                $x = strrpos($Path, "/", -2) + 1;

                print "<a href=\"#\" onclick=\"GetFileListing('".substr($Path, 0, $x)."');\" style=\"text-decoration:none;\"><img width=\"16\" height=\"16\" src=\"./js/images/folder-up.gif\"> Back...</a><br>";
        }



	print "<form name=\"ListingForm\" id=\"ListingForm\" method=\"post\">";

	print "<div style=\"visibility: hidden; display: none;\">";
	print "<input type=\"hidden\" name=\"Path\" value=\"".$Path."\">";
	print "<input type=\"hidden\" name=\"URL\" value=\"".$URL."\">";

	print "<textarea name=\"FilesAndFolders\"></textarea>";
	print "</div>";
	
	print "<table id=\"FilesAndFolders\" width=\"100%\" border=\"0\" style=\"border:0px solid blue;\" cellpadding=\"0\" cellspacing=\"0\">";

	print "<thead>";
	print "<tr>";
	print "<td width=\"5%\">&nbsp;</td>";
	print "<td width=\"35%\"><b>Path</b></td>";
	print "<td width=\"25%\"><b>Date</b></td>";
	print "<td width=\"10%\"><b>Size</b></td>";
	print "<td width=\"10%\"><b>Perm</b></td>";
	print "<td width=\"10%\">&nbsp;</td>";
	print "<td width=\"5%\">&nbsp;</td>";
	print "</tr>";

	print "<tr>";
	print "<td colspan=\"6\"><img border=\"0\" src=\"./js/images/folder.gif\"> <b>".$Path."</b></td>";
	print "</tr>";
	print "</thead>";

	$FileArray = array();
 	if ($handle = opendir($Path)) 
	{
		
		print "<tbody>";
	
		$x = 0;

	        /* This is the correct way to loop over the directory. */
	        while (false !== ($file = readdir($handle))) 
		{
			if($file != "." && $file != "..")
			{
				array_push($FileArray, $file);
			}

		}
	
		natcasesort($FileArray);
	
		foreach($FileArray as $file)
		{
			if(is_dir($Path.$file))
			{
				//print "<tr onmousedown=\"RowClick(this);\" id=\"directory".str_replace("/", "_", $Path.$file)."\">";
				print "<tr name=\"FileFolderRow\" onmousedown=\"RowClick(this);\" id=\"directory".$Path.$file."\">";
				print "<td>&nbsp;</td>";
			        print "<td><a href=\"#\" onclick=\"GetFileListing('".$Path.$file."');\" style=\"text-decoration: none;\"><img border=0 src=\"./js/images/folder-closed.gif\"> ".$file."</a></td>";
				print "<td>". date ("M d Y H:i:s", filemtime($Path.$file)) ."</td>";
				print "<td>&nbsp;</td>";
				print "<td><span onclick=\"MakeDivVisible('perms_".$x."');\" id=\"perms_span_".$x."\">".substr(sprintf('%o', fileperms($Path.$file)), -4)."</span><div id=\"perms_".$x."\" style=\"padding: 10px; background-color: white; width: 80px; height: 80px; visibility: hidden; border: 1px solid red; position: absolute; margin-top: -25px; margin-left: -35px; display: none;\"><table border=\"0\"><tr><td><input id=\"perms_input_".$x."\" type=\"text\" style=\"border:1px solid #000099; height:25px; width:50px;\" value=\"".substr(sprintf('%o', fileperms($Path.$file)), -4)."\"></td></tr><tr><td><span onclick=\"SavePermissions('".$Path.$file."', document.getElementById('perms_input_".$x."').value, 'perms_span_".$x."', 'perms_".$x."');\">Save</span></td></tr><tr><td><span onclick=\"MakeDivInvisible('perms_".$x."');\">Cancel</span></td></tr></table></div></td>";
				print "<td>&nbsp;</td>";
				print "<td><a onclick=\"DeleteDirectory('".$Path.$file."', CurrentPath);\"><img src=\"./images/delete.png\" title=\"Delete\"></a></td>";
				print "</tr>";
			}
			
			$x++;
	        }
			  
		foreach($FileArray as $file)
		{
			if(! is_dir($Path.$file))
			{
				//print "<tr onmousedown=\"RowClick(this);\" id=\"file".str_replace("/", "_", $Path.$file)."\">";
				print "<tr name=\"FileFolderRow\" onmousedown=\"RowClick(this);\" id=\"file".$Path.$file."\">";
				print "<td>&nbsp;</td>";
			     	print "<td><img border=0 src=\"./images/icons/".GetFileTypeIcon($file)."\"> ".$file."</td>";
				print "<td>". date ("M d Y H:i:s", filemtime($Path.$file)) ."</td>";
				print "<td>".ConvertFromBytes(filesize($Path.$file))."</td>";
				print "<td><span onclick=\"MakeDivVisible('perms_".$x."');\" id=\"perms_span_".$x."\">".substr(sprintf('%o', fileperms($Path.$file)), -4)."</span><div id=\"perms_".$x."\" style=\"padding: 10px; background-color: white; width: 80px; height: 80px; visibility: hidden; border: 1px solid red; position: absolute; margin-top: -25px; margin-left: -35px; display: none;\"><table border=\"0\"><tr><td><input id=\"perms_input_".$x."\" type=\"text\" style=\"border:1px solid #000099; height:25px; width:50px;\" value=\"".substr(sprintf('%o', fileperms($Path.$file)), -4)."\"></td></tr><tr><td><span onclick=\"SavePermissions('".$Path.$file."', document.getElementById('perms_input_".$x."').value, 'perms_span_".$x."', 'perms_".$x."');\">Save</span></td></tr><tr><td><span onclick=\"MakeDivInvisible('perms_".$x."');\">Cancel</span></td></tr></table></div></td>";
			
				if(FileIsEditable($file))
				{
					print "<td><a href=\"text_editor.php?FileName=".$Path.$file."\" target=\"_blank\"><img src=\"./images/edit.png\" title=\"Edit\"></a> ";

					if(FileIsCodeEditable($file))
					{
						print "<a href=\"code_editor/editor.php?FileName=".$Path.$file."\" target=\"_blank\"><img src=\"./images/CodeEditor.png\" title=\"Code Editor\"></a>";
					}
					
					if(FileIsHTMLEditable($file))
					{
						print "<a href=\"ckeditor/html_editor.php?FileName=".$Path.$file."\" target=\"_blank\"><img src=\"./images/html.png\" title=\"HTML Editor\"></a>";
					}

					print "</td>";

				}
				else
				{
					print "<td>&nbsp;</td>";
				}
				print "<td><a onclick=\"DeleteSingleFile('".$Path.$file."', CurrentPath);\"><img src=\"./images/delete.png\" title=\"Delete\"></a></td>";
				print "</tr>";
			}
			
			$x++;
	        			  
		}
	

		//print "<input type=\"button\" onclick=\"CheckForms();\" value=\"BUTTON\">";
		print "</tbody>";
	       	closedir($handle);

    	}

	print "</table>";
  		
	print "</form>"; 

      	?>

