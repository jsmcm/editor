	
	FileArray = Array();

	function AddToFileArray(File)
	{
		for(x = 0; x < FileArray.length; x++)
		{
			if(FileArray[x] == File)
			{
				return;
			}
		}

		FileArray.push(File);
	}


	
	var SelectedFormCounter = 0;	

	xmlhttp = null;
	count = 0;
	var d = new Date();

	$(document).ready(function(){ 
		$("#browser").treeview({ 
			toggle: function() { 
				console.log("%s was toggled.", $(this).find(">span").text()); 
			} 
		}); 
	}); 


	var KillDoubleClick = false;


	function ChangeSelectedState(obj)
	{
		alert(obj.name);
	}

	function ExpandFolder(URL, Path)
	{

		//alert("In d: URL: '" + URL + "'");
		//alert("In d: Path: '" + Path + "'");

		if(KillDoubleClick == false)
		{
			KillDoubleClick = true;
			window.location = "index.php?URL=" + URL + "&Path="+Path;
		}
	}
	
	function DoDeleteDirectory(DirectoryName)
	{
                xmlhttp = null;
                
                if (window.XMLHttpRequest)
               	{
                        // code for IE7+, Firefox, Chrome, Opera, Safari
                	xmlhttp=new XMLHttpRequest();
                }
                else
              	{                               
                        // IE6, IE5
                	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                }

                RndString = d.getFullYear() + "" + d.getMonth() + "" + d.getDate() + ""  + d.getHours() + "" + d.getMinutes() + "" + d.getSeconds() + "" + count++;
    
                xmlhttp.open("GET",'./ajax/DeleteDirectory.php?DirectoryName=' + DirectoryName + '&C=' + RndString,false);
                xmlhttp.send(null);

       		return xmlhttp.responseText;
	}

	function DeleteDirectory(DirectoryName, Path)
	{
		if(confirm("Really delete: " + DirectoryName + "?\r\n\r\nTHIS WILL DELETE ALL FILES AND FOLDERS WITHIN IT!"))
		{

			MakeDivVisible("ProgressSpinner");

			clearAll();
			ReturnValue = DoDeleteDirectory(DirectoryName);
                        if(ReturnValue == "deleted")
                        {

				GetFileListing(Path);
                        }
                        else
                        {
				MakeDivInvisible("ProgressSpinner");
                                alert("Error! " + ReturnValue);
                        }


		}
	}

	function ReloadListing(Path)
	{
		
			location.reload(true);
			return;
	
			clearAll();

                        xmlhttp = null;
                        //alert("at top");
                        if (window.XMLHttpRequest)
                        {
                                // code for IE7+, Firefox, Chrome, Opera, Safari
                                xmlhttp=new XMLHttpRequest();
                        }
                        else
                        {                               
                                // IE6, IE5
                                xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                        }

                        RndString = d.getFullYear() + "" + d.getMonth() + "" + d.getDate() + ""  + d.getHours() + "" + d.getMinutes() + "" + d.getSeconds() + "" + count++;
    
                        xmlhttp.open("GET",'./Listing.php?Path=' + Path + '&C=' + RndString,false);
                        xmlhttp.send(null);

                        if(xmlhttp.responseText != "")
                        {
				elem = document.getElementById("ListingTable");
				elem.innerHTML = xmlhttp.responseText;
                        }

	}

	
	
	function DoDeleteFile(FileName)
	{
		
                xmlhttp = null;
                if (window.XMLHttpRequest)
              	{
                        // code for IE7+, Firefox, Chrome, Opera, Safari
                	xmlhttp=new XMLHttpRequest();
                }
                else
              	{                               
                        // IE6, IE5
                	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                }

                RndString = d.getFullYear() + "" + d.getMonth() + "" + d.getDate() + ""  + d.getHours() + "" + d.getMinutes() + "" + d.getSeconds() + "" + count++;
    
                xmlhttp.open("GET",'./ajax/DeleteFile.php?FileName=' + FileName + '&C=' + RndString,false);
                xmlhttp.send(null);

       		return xmlhttp.responseText;
	}

	

	function DeleteSingleFile(FileName, Path)
	{
		if(confirm("Really delete: " + FileName + "?"))
		{
			MakeDivVisible("ProgressSpinner");
   			ReplyValue = DoDeleteFile(FileName);
 
                        if(ReplyValue == "deleted")
                        {
                                //location.reload();
				GetFileListing(Path);
                        }
                        else
                        {
				MakeDivInvisible("ProgressSpinner");
                                alert("Error! " + ReplyValue);
                        }
		}
	}

	
	function validate(FileName)
	{
    		var alphaExp = /^([A-Za-z_\-\s0-9\.]+)$/;
    
		//alert("Validate 1");		

		if(FileName.match(alphaExp))
		{
			//alert("validate 2");
        		return true;
	  	}
		else
		{
			//alert("validate 3");
		        return false;
		}
	}

	function AddNewFile(Path)
	{
		//alert(1);
		var FileName = prompt("Enter the new file name", "");

			
		MakeDivVisible("ProgressSpinner");

		if(validate(FileName) == true)
		{


			xmlhttp = null;
    			//alert("at top");
    			if (window.XMLHttpRequest)
    			{
            			// code for IE7+, Firefox, Chrome, Opera, Safari
            			xmlhttp=new XMLHttpRequest();
   	 		}
    			else
    			{				
            			// IE6, IE5
            			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    			}

    			RndString = d.getFullYear() + "" + d.getMonth() + "" + d.getDate() + ""  + d.getHours() + "" + d.getMinutes() + "" + d.getSeconds() + "" + count++;
    
    			xmlhttp.open("GET",'./ajax/CreateFile.php?FileName=' + Path + FileName + '&C=' + RndString,false);
    			xmlhttp.send(null);

    			//alert("xmlhttp.responseText: " + xmlhttp.responseText);
    
    			if(xmlhttp.responseText == "created")
    			{
				
				//location.reload();
				GetFileListing(Path);
    			}
   	 		else
    			{
				MakeDivInvisible("ProgressSpinner");
				alert("Error! " + xmlhttp.responseText);
    			}




		}
		else
		{
			MakeDivInvisible("ProgressSpinner");
			alert("Not a valid file name");
		}
		
	}

	function AddNewDirectory(Path)
	{
		var DirectoryName = prompt("Enter the new folder name", "");

		MakeDivVisible("ProgressSpinner");
		if(validate(DirectoryName) == true)
		{

			xmlhttp = null;
    			//alert("at top");
    			if (window.XMLHttpRequest)
    			{
            			// code for IE7+, Firefox, Chrome, Opera, Safari
            			xmlhttp=new XMLHttpRequest();
   	 		}
    			else
    			{				
            			// IE6, IE5
            			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    			}

    			RndString = d.getFullYear() + "" + d.getMonth() + "" + d.getDate() + ""  + d.getHours() + "" + d.getMinutes() + "" + d.getSeconds() + "" + count++;
    
    			xmlhttp.open("GET",'./ajax/CreateDirectory.php?DirectoryName=' + Path + DirectoryName + '&C=' + RndString,false);
    			xmlhttp.send(null);

    			//alert("xmlhttp.responseText: " + xmlhttp.responseText);
    
    			if(xmlhttp.responseText == "created")
    			{
				
				//location.reload();
				GetFileListing(Path);
    			}
   	 		else
    			{
				MakeDivInvisible("ProgressSpinner");
				alert("Error! " + xmlhttp.responseText);
    			}

		}
		else
		{
			MakeDivInvisible("ProgressSpinner");
			alert("Not a valid folder name");
		}
		
	}
	
	function ToggleAll()
	{
		for(i=0; i<document.ListingForm.elements.length; i++)
		{
			if(document.ListingForm.elements[i].type=="checkbox")
			{
				if(document.ListingForm.elements[i].checked == true)
				{
					document.ListingForm.elements[i].checked = false;
				}
				else
				{
					document.ListingForm.elements[i].checked =true;
				}
			}
		}
	}

	function Rename(Path)
	{
		if(FileArray.length > 1)
		{
			alert("Please select only a single file or folder to rename!");
			return false;
		}

		if(FileArray.length < 1)
		{
			alert("Please select a file or folder to rename!");
			return false;
		}

		OldFileName = FileArray[0];
		FileName = OldFileName;

		x = FileName.indexOf("/");

		while( x > -1)
		{
			FileName = FileName.substring(x + 1);
			x = FileName.indexOf("/");
		}


		NewFileName = prompt("New name for '" + FileName + "'?", FileName);
		if( ! NewFileName)
		{
			NewFileName = "";
		}
		
		
		if(NewFileName != "")
		{

			xmlhttp = null;
		      	if (window.XMLHttpRequest)
		        {
		        	// code for IE7+, Firefox, Chrome, Opera, Safari
		               	xmlhttp=new XMLHttpRequest();
		       	}
		        else
		        {                               
		                // IE6, IE5
		            	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        		}
	
		        RndString = d.getFullYear() + "" + d.getMonth() + "" + d.getDate() + ""  + d.getHours() + "" + d.getMinutes() + "" + d.getSeconds() + "" + count++;
    
		 	xmlhttp.open("GET",'./ajax/Rename.php?OldName=' + OldFileName + '&NewName=' + NewFileName + '&C=' + RndString,false);
		        xmlhttp.send(null);

       			if(xmlhttp.responseText == "")
			{	
				GetFileListing(Path);
			}
			else
			{
				alert(xmlhttp.responseText);
			}

		}
	}

	function Download()
	{
		if(FileArray.length > 1)
		{
			alert("Please select only a single file to download!");
			return false;
		}

		if(FileArray.length < 1)
		{
			alert("Please select a file to download!");
			return false;
		}

		FileName = FileArray[0];

		if(FileName.substr(0, 3) == "dir")
		{
			alert("You cannot directly download folders. Please zip it first, then download the zip file!");
			return false;
		}

		x = FileName.indexOf("/");
		//alert(FileName);

		if(x > -1)
		{
			FileName = FileName.substring(x);
		}

		//alert(FileName);


		
		if(FileName != "")
		{
			document.ListingForm.FilesAndFolders.value = FileName;

			document.ListingForm.action="DoDownload.php";
			document.ListingForm.submit();
		}
	}





	function Copy(DeleteSource, Path)
	{
		Wording = "copy";
		if(DeleteSource == 1)
		{
			Wording = "move";
		}

		if(FileArray.length > 1)
		{
			alert("Please select only a single file or folder to " + Wording + "!");
			return false;
		}

		if(FileArray.length < 1)
		{
			alert("Please select a file or folder to " + Wording + "!");
			return false;
		}

		OldFileName = FileArray[0];
		FileName = "";

		if(OldFileName.substr(0, 4) == "file")
		{
			FileName = OldFileName.substr(4);
		}
		else if(OldFileName.substr(0, 9) == "directory")
		{
			FileName = OldFileName.substr(9);
		}

		NewPath = prompt(Wording + " " + FileName + " to ...?", FileName);
		if( ! NewPath)
		{
			NewPath = "";
		}
		
		if(NewPath != "")
		{
			xmlhttp = null;
		      	if (window.XMLHttpRequest)
		        {
		        	// code for IE7+, Firefox, Chrome, Opera, Safari
		               	xmlhttp=new XMLHttpRequest();
		       	}
		        else
		        {                               
		                // IE6, IE5
		            	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        		}
	
		        RndString = d.getFullYear() + "" + d.getMonth() + "" + d.getDate() + ""  + d.getHours() + "" + d.getMinutes() + "" + d.getSeconds() + "" + count++;
    
		 	xmlhttp.open("GET",'./ajax/Copy.php?OldFile=' + OldFileName + '&NewPath='+ NewPath + '&DeleteSource=' + DeleteSource + '&C=' + RndString,false);
		        xmlhttp.send(null);

       			if(xmlhttp.responseText == "")
			{	
				GetFileListing(Path);
			}
			else
			{
				alert(xmlhttp.responseText);
			}
			
		}
	}


	function BulkDelete(Path)
	{	

		FileString = "";
		FolderString = "";

		if(FileArray.length > 0)
		{

			Message = "Really delete " + FileArray.length + " files and folders?";
	
			if(confirm(Message))
			{
				MakeDivVisible("ProgressSpinner");

				for(x = 0; x < FileArray.length; x++)
				{
					//alert(FileArray[x]);
					//FileArray[x] = FileArray[x].replace(/_/g, "/");

					if(FileArray[x].substr(0, 4) == "file")
					{
						FileString = FileString + "\"" + FileArray[x].substr(4) + "\",";
					}
					else
					{
						FolderString = FolderString + "\"" + FileArray[x].substr(9) + "\",";
					}
				}

				if(FileString.length > 1)
				{
					FileString = FileString.substr(0, FileString.length - 1);
				}

				if(FolderString.length > 1)
				{
					FolderString = FolderString.substr(0, FolderString.length - 1);
				}
	               	
				JSONString = "{\"Files\":[" + FileString + "],\"Folders\":[" + FolderString + "]}";
                
				xmlhttp = null;
		                if (window.XMLHttpRequest)
		              	{
		                        // code for IE7+, Firefox, Chrome, Opera, Safari
		                	xmlhttp=new XMLHttpRequest();
		                }
		                else
		              	{                               
		                        // IE6, IE5
		                	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        		        }
	
		                RndString = d.getFullYear() + "" + d.getMonth() + "" + d.getDate() + ""  + d.getHours() + "" + d.getMinutes() + "" + d.getSeconds() + "" + count++;
    
		                xmlhttp.open("GET",'./ajax/BulkDelete.php?FilesAndFolders=' + JSONString + '&C=' + RndString,false);
		                xmlhttp.send(null);

				GetFileListing(Path);

			}
		}
		else if(FileArray.length == 0)
		{
			alert("No files or folders selected");
		}
	}

	function UnzipFile(Path)
	{	
		MakeDivVisible("ProgressSpinner");

		if(FileArray.length == 1)
		{

			FileName = FileArray[0];
			FileName = FileName.substr(4);
			//alert(FileName);
			
			Extension = FileName.substr(FileName.length - 3);
			//alert(Extension);
			//alert(Extension.toLowerCase());

			if( Extension.toLowerCase() != "zip")
			{
				alert("File does not have a .zip extention");
				MakeDivInvisible("ProgressSpinner");
				return;
			}
	               	
			
				xmlhttp = null;
			      	if (window.XMLHttpRequest)
			        {
			        	// code for IE7+, Firefox, Chrome, Opera, Safari
			               	xmlhttp=new XMLHttpRequest();
			       	}
			        else
			        {                               
			                // IE6, IE5
			            	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	        		}
		
			        RndString = d.getFullYear() + "" + d.getMonth() + "" + d.getDate() + ""  + d.getHours() + "" + d.getMinutes() + "" + d.getSeconds() + "" + count++;
	    
				//alert(JSONString)
					
			 	xmlhttp.open("GET",'./ajax/DoUnzip.php?Path=' + Path + '&FilesAndFolders=' + FileName + '&C=' + RndString,false);
			        xmlhttp.send(null);
	
	       			if(xmlhttp.responseText == "")
				{	
					GetFileListing(Path);
				}
				else
				{
					alert(xmlhttp.responseText);
					MakeDivInvisible("ProgressSpinner");
				}


		}
		else
		{
			MakeDivInvisible("ProgressSpinner");
			alert("Please select only a single zip file!");
		}
	}
	
	function UntarFile(Path)
	{	
		MakeDivVisible("ProgressSpinner");

		if(FileArray.length == 1)
		{

			FileName = FileArray[0];
			FileName = FileName.substr(4);
			
			Extension = FileName.substr(FileName.length - 6);

			if( Extension.toLowerCase() != "tar.gz")
			{
				alert("File does not have a .tar.gz extention");
				MakeDivInvisible("ProgressSpinner");
				return;
			}
				xmlhttp = null;
			      	if (window.XMLHttpRequest)
			        {
			        	// code for IE7+, Firefox, Chrome, Opera, Safari
			               	xmlhttp=new XMLHttpRequest();
			       	}
			        else
			        {                               
			                // IE6, IE5
			            	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	        		}
		
			        RndString = d.getFullYear() + "" + d.getMonth() + "" + d.getDate() + ""  + d.getHours() + "" + d.getMinutes() + "" + d.getSeconds() + "" + count++;
	    
					
			 	xmlhttp.open("GET",'./ajax/DoUntar.php?FilesAndFolders=' + FileName + '&C=' + RndString,false);
			        xmlhttp.send(null);
	
	       			if(xmlhttp.responseText == "")
				{	
					GetFileListing(Path);
				}
				else
				{

					alert(xmlhttp.responseText);
					MakeDivInvisible("ProgressSpinner");
				}

	               	
		}
		else
		{
			MakeDivInvisible("ProgressSpinner");
			alert("Please select only a single tar.gz file!");
		}
	}
	
	
	function ZipFiles(Path)
	{	

		FileString = "";
		FolderString = "";

		MakeDivVisible("ProgressSpinner");
		if(FileArray.length > 0)
		{

				for(x = 0; x < FileArray.length; x++)
				{
					if(FileArray[x].substr(0, 4) == "file")
					{
						FileString = FileString + "\"" + FileArray[x].substr(4) + "\",";
					}
					else
					{
						FolderString = FolderString + "\"" + FileArray[x].substr(9) + "\",";
					}
				}

				if(FileString.length > 1)
				{
					FileString = FileString.substr(0, FileString.length - 1);
				}

				if(FolderString.length > 1)
				{
					FolderString = FolderString.substr(0, FolderString.length - 1);
				}
	               	
			

				JSONString = "{\"Files\":[" + FileString + "],\"Folders\":[" + FolderString + "]}";
				xmlhttp = null;
			      	if (window.XMLHttpRequest)
			        {
			        	// code for IE7+, Firefox, Chrome, Opera, Safari
			               	xmlhttp=new XMLHttpRequest();
			       	}
			        else
			        {                               
			                // IE6, IE5
			            	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	        		}
		
			        RndString = d.getFullYear() + "" + d.getMonth() + "" + d.getDate() + ""  + d.getHours() + "" + d.getMinutes() + "" + d.getSeconds() + "" + count++;
	    
				//alert(JSONString)
					
			 	xmlhttp.open("GET",'./ajax/Zip.php?FilesAndFolders=' + JSONString + '&C=' + RndString,false);
			        xmlhttp.send(null);
	
	       			if(xmlhttp.responseText == "")
				{	
					GetFileListing(Path);
				}
				else
				{
					MakeDivInvisible("ProgressSpinner");
				}

		}
		else if(FileArray.length == 0)
		{
			alert("No files or folders selected");
			MakeDivInvisible("ProgressSpinner");
		}
	}

	function Refresh(Path)
	{
		MakeDivVisible("ProgressSpinner");
		GetFileListing(Path);
	}

	function TarFiles(Path)
	{	

		FileString = "";
		FolderString = "";
					
		MakeDivVisible("ProgressSpinner");

		if(FileArray.length > 0)
		{

				for(x = 0; x < FileArray.length; x++)
				{
					if(FileArray[x].substr(0, 4) == "file")
					{
						FileString = FileString + "\"" + FileArray[x].substr(4) + "\",";
					}
					else
					{
						FolderString = FolderString + "\"" + FileArray[x].substr(9) + "\",";
					}
				}

				if(FileString.length > 1)
				{
					FileString = FileString.substr(0, FileString.length - 1);
				}

				if(FolderString.length > 1)
				{
					FolderString = FolderString.substr(0, FolderString.length - 1);
				}
	               	
				JSONString = "{\"Files\":[" + FileString + "],\"Folders\":[" + FolderString + "]}";
				xmlhttp = null;
			      	if (window.XMLHttpRequest)
			        {
			        	// code for IE7+, Firefox, Chrome, Opera, Safari
			               	xmlhttp=new XMLHttpRequest();
			       	}
			        else
			        {                               
			                // IE6, IE5
			            	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	        		}
		
			        RndString = d.getFullYear() + "" + d.getMonth() + "" + d.getDate() + ""  + d.getHours() + "" + d.getMinutes() + "" + d.getSeconds() + "" + count++;
	    

			 	xmlhttp.open("GET",'./ajax/Tar.php?FilesAndFolders=' + JSONString + '&C=' + RndString,false);
			        xmlhttp.send(null);
	
	       			if(xmlhttp.responseText == "")
				{	
					GetFileListing(Path);
				}
				else
				{
					alert(xmlhttp.responseText);
					MakeDivInvisible("ProgressSpinner");
				}

		}
		else if(FileArray.length == 0)
		{
			MakeDivInvisible("ProgressSpinner");
			alert("No files or folders selected");
		}
	}
