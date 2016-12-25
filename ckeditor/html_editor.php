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

$Contents = file_get_contents($FileName);
?>

<!DOCTYPE html>
<html>
<head>

        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js" type="text/javascript"></script>
        
        <script language="javascript" type="text/javascript">

                function HideSuccessDiv()
                {
                        elem = document.getElementById("PleaseWait");
                        elem.style.visibility = "hidden";
                        elem.style.display = "none";
                }


                // callback functions
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

          </script>
          
     <script src="ckeditor.js"></script>
          
		<script>

                function save_file(content)
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
		
                        xhr.open('POST', '../code_editor/save_code_editor.php');
                        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                        xhr.send("FileName=<?php print $FileName; ?>&FileContents=" + content);

                }

          </script>
     <meta charset="utf-8">
     <title>HTML Editor</title>
     <link href="sample.css" rel="stylesheet">
</head>
<body>

<div id="PleaseWait" style="visibility: hidden; display: none; width:200px; height:100px; background:#E5E5E5; border: solid 1px black; left:350px; top:200px; position: absolute; z-index:10000; color:#00029c; font-size:24px; font-weight:bold"><p><center>File Saved</center>
</div>


     <form action="sample_posteddata.php" method="post">
          <textarea id="editor1" name="FileContents" style="height:100%; width:100%">
               <?php print htmlspecialchars(trim($Contents)); ?>
          </textarea>
     </form>
          
     <script>

	  // This call can be placed at any point after the
	  // <textarea>, or inside a <head><script> in a
	  // window.onload event handler.

	  // Replace the <textarea id="editor"> with an CKEditor
	  // instance, using default configurations.
	  CKEDITOR.replace( 'editor1',{uiColor: '#ADD4FF',fullPage: true,
   allowedContent: true , 
    on: {
        save: function(evt)
        {
            // Do something here, for example:
           	save_file(evt.editor.getData());
 
            // If you want to prevent the form submit (if your editor is in a <form> element), return false here
            return false;
        }}
    });
		   






		   CKEDITOR.on('instanceReady',
		   function( evt )
		   {
		      var editor = evt.editor;
		      editor.execCommand('maximize');

		   });
    	</script> 
</body>
</html>
