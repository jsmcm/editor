<?php
session_start();


$FileName = "";
if(isset($_GET["FileName"]))
{
        $FileName = $_GET["FileName"];
}

if($FileName == "")
{
        print "No file select";
        exit();
}

$Extention = "html";

$x = strrpos($FileName, ".");
if($x > -1)
{
	$Extention = substr($FileName, $x + 1);
}

if($Extention == "pl")
{
	$Extention = "perl";
}
else if($Extention == "htm")
{
	$Extention = "html";
}



$Contents = file_get_contents($FileName);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>EditArea - the code editor in a textarea</title>

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js" type="text/javascript"></script>

	<script language="Javascript" type="text/javascript" src="./edit_area/edit_area_full.js"></script>
	<script language="Javascript" type="text/javascript">
		// initialisation
		
		editAreaLoader.init({
			id: "example_2"	// id of the textarea to transform	
			,start_highlight: true
			,allow_toggle: false
			,language: "en"
			,syntax: "<?php print $Extention; ?>"	
			,toolbar: "search, go_to_line, word_wrap, |, undo, redo, |, select_font, |, syntax_selection, |, change_smooth_selection, highlight, reset_highlight, |, save, |, help"
			,syntax_selection_allow: "css,html,js,php,python,vb,xml,c,cpp,sql,basic,pas,perl"
			,is_multi_files: false
			,allow_resize: "both"
			,EA_load_callback: "editAreaLoaded"
			,show_line_colors: true
			,replace_tab_by_spaces: 5
			,fullscreen: true
			,word_wrap: true
			,save_callback: "save_file"
		});
		
		
		function HideSuccessDiv()
        	{
	                elem = document.getElementById("PleaseWait");
	                elem.style.visibility = "hidden";
	                elem.style.display = "none";
	        }
	

		// callback functions
		function save_file(id, content)
		{
			

			var xhr;
			if (window.XMLHttpRequest) 
			{
				xhr = new XMLHttpRequest();
			}
			else if (window.ActiveXObject) 
			{
        			xhr = new ActiveXObject("Msxml2.XMLHTTP");
    			}
    			else 
			{
        			throw new Error("Ajax is not supported by this browser");
    			}

   			xhr.onreadystatechange = function () 
			{
        			if (xhr.readyState === 4) 
				{
            				if (xhr.status == 200 && xhr.status < 300) 
					{
		                		if(xhr.responseText == "saved")
						{
							elem = document.getElementById("PleaseWait");
							elem.style.visibility = "visible";
							elem.style.display = "inline";

							setTimeout("HideSuccessDiv()", 1500);
						}
						else
						{
							alert("An error occurred, file not saved");
						}
       	   				}
	       			}
    			}


			content = escape(content);
			content = content.replace(/\+/g, "%2B");

   			xhr.open('POST', 'save_code_editor.php');
    			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    			xhr.send("FileName=<?php print $FileName; ?>&FileContents=" + content);

		}

		
		function TransformText(str)
		{
		  // http://kevin.vanzonneveld.net
		  // +   original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
		  // +   improved by: Ates Goral (http://magnetiq.com)
		  // +   bugfixed by: Onno Marsman
		  // +   improved by: Rafał Kukawski (http://blog.kukawski.pl)
		  // *     example 1: str_rot13('Kevin van Zonneveld');
		  // *     returns 1: 'Xriva ina Mbaariryq'
		  // *     example 2: str_rot13('Xriva ina Mbaariryq');
		  // *     returns 2: 'Kevin van Zonneveld'
		  // *     example 3: str_rot13(33);
		  // *     returns 3: '33'
		  return (str + '').replace(/[a-z]/gi, function (s) {
		    return String.fromCharCode(s.charCodeAt(0) + (s.toLowerCase() < 'n' ? 13 : -13));
		  });
		}

		
		function toogle_editable(id)
		{
			editAreaLoader.execCommand(id, 'set_editable', !editAreaLoader.execCommand(id, 'is_editable'));
		}
	
	</script>
</head>
<body>

<div id="PleaseWait" style="visibility: hidden; display: none; width:200px; height:100px; background:#E5E5E5; border: solid 1px black; left:350px; top:200px; position: absolute; z-index:1000; color:#00029c; font-size:24px; font-weight:bold"><p><center>File Saved</center>
</div>
<div style="position:absolute; top:0; left:0; z-index:10;">
<form action='' method='post' name="CodeEditor">
		<textarea id="example_2" style="height: 250px; width: 100%;" name="FileContents"><?php print htmlspecialchars(trim($Contents)); ?></textarea>
</form>
</div>
</body>
</html>
