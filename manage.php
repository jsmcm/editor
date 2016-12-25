<?php

session_start();

function __autoload($classname)
{
        require_once($_SERVER["DOCUMENT_ROOT"]."/includes/classes/class.".$classname.".php");
}

$oUser = new User();
$oPackage = new Package();
$oLog = new Log();
$oDomain = new Domain();


$oLog->WriteLog("DEBUG", "/passwd/manage.php...");

$client_id = $oUser->Getclient_id();
if($client_id < 1)
{
        $oLog->WriteLog("DEBUG", "/passwd/manage.php -> client_id not set, redirecting to /index.php");
        header("Location: /index.php");
        exit();
}

$oLog->WriteLog("DEBUG", "/passwd/manage.php -> client_id set, continuing");


if(!isset($_REQUEST["URL"]))
{
	header("Location: index.php");
	exit();
}

$URL = $_REQUEST["URL"];

if(!isset($_REQUEST["Path"]))
{
	header("Location: index.php");
	exit();
}


if( ($oDomain->GetDomainOwnerFromDomainName($URL) != $client_id) && ($oUser->Role != "admin") )
{
        header("location: index.php?Notes=You do not have permission to access this sites detail");
        exit();
}

$HTAccessPath = $_REQUEST["Path"];


$PasswordPath = substr($HTAccessPath, 0, strpos($HTAccessPath, "public_html")).".passwd/".substr($HTAccessPath, strpos($HTAccessPath, "public_html"))."/passwd";

$HTAccessPath = $HTAccessPath."/.htaccess";

$PostData = "Path=".$HTAccessPath;

$c = curl_init();
curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($c, CURLOPT_POSTFIELDS,  $PostData);
curl_setopt($c, CURLOPT_POST, 1);
curl_setopt($c, CURLOPT_URL, "http://".$URL.":20001/read.php");

$ResultString = trim(curl_exec($c));
curl_close($c);

$DirectoryProtected = false;
$UserArray = array();
$Title = "";
$PasswordFileFromHTAccess = "";

if($ResultString != "")
{
	//print "<p>HTACCESS<p>";
	//print $ResultString;
	//print "<p>";
	
	// Header
	// PasswordFileFromHTAccess

	if(strstr($ResultString, "AuthType Basic"))
	{
		$DirectoryProtected = true;
	}

	$Title = trim(substr($ResultString, strpos($ResultString, "AuthName") + 8));

	$Title = trim(substr($Title,0, strpos($Title, "\n")));

	if(substr($Title, 0, 1) == "\"")
	{
		$Title = substr($Title, 1);
	}

	if(substr($Title, strlen($Title) - 1) == "\"")
	{
		$Title = substr($Title, 0, strlen($Title) - 1);
	}

	//print "Title: '".$Title."'<p>";
	



	$PasswordFileFromHTAccess = trim(substr($ResultString, strpos($ResultString, "AuthUserFile") + 12));
	
	$PasswordFileFromHTAccess = trim(substr($PasswordFileFromHTAccess, 0, strpos($PasswordFileFromHTAccess, "\n")));

	if(substr($PasswordFileFromHTAccess, 0, 1) == "\"")
	{
		$PasswordFileFromHTAccess = substr($PasswordFileFromHTAccess, 1);
	}

	if(substr($PasswordFileFromHTAccess, strlen($PasswordFileFromHTAccess) - 1) == "\"")
	{
		$PasswordFileFromHTAccess = substr($PasswordFileFromHTAccess, 0, strlen($PasswordFileFromHTAccess) - 1);
	}

	//print "PasswordFileFromHTAccess: '".$PasswordFileFromHTAccess."'<p>";
}

$ActualPasswordPath = "";
if( ($PasswordFileFromHTAccess != "") && ($PasswordFileFromHTAccess != $PasswordPath) )
{
	if(file_exists($PasswordFileFromHTAccess))
	{
		$ActualPasswordPath = $PasswordFileFromHTAccess;
		$UserArray = explode("\n", file_get_contents($PasswordFileFromHTAccess));
	}
}
else
{
	if(file_exists($PasswordPath))
	{
		$ActualPasswordPath = $PasswordPath;
		$UserArray = explode("\n", file_get_contents($PasswordPath));
	}
}

//for($x = 0; $x < count($UserArray); $x++)
//{
//	print ($x + 1).$UserArray[$x]."<br>";
//}

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">


<title>Dashboard | Web Control Panel</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />


<style type="text/css">
<!--
@import url("/includes/styles/tablestyle.css");
-->
</style>

<?php
include($_SERVER["DOCUMENT_ROOT"]."/includes/styles/Styles.inc");
?>

<script src="/includes/javascript/jquery.js"></script>
<script src="/includes/javascript/main.js"></script>
<script src="/includes/javascript/sorttable.js"></script>

<script language="javascript">

function ValidateAdd()
{
	Password = document.Users.Password.value;
	ConfirmPassword = document.Users.ConfirmPassword.value;

	if(Password != ConfirmPassword)
	{
		alert("Those passwords don't match!");
		document.Users.Password.focus();

		document.Users.Password.value = "";
		document.Users.ConfirmPassword.value = "";

		return false;
	}

	if(Password == "")
	{
		alert("Password cannot be blank!");
		document.Users.Password.focus();
		return false;
	}
	
	return true;

}

function ValidateDelete(User)
{
	if(User.length == 0)
	{
		return false;
	}

	if(confirm("Really delete: " + User))
	{
		return true;
	}

	return false;
}
</script>

</head>

<body style="margin:0; background: #ededed">

<?php
include($_SERVER["DOCUMENT_ROOT"]."/includes/PageParts/TopBar.inc");
include($_SERVER["DOCUMENT_ROOT"]."/includes/PageParts/TopNav.inc");

?>


<div id="wrap">
<p>


<div align="center" style="width:100%; background:white; padding-top:20px; padding-bottom:20px; border-style:dotted; border-width:1px">

<?php
if(isset($_REQUEST["Notes"]))
{
	print "<p><font color=\"red\">".$_REQUEST["Notes"]."</font><p>";
}
?>



<h1><?php print $_REQUEST["Path"]; ?></h1>

<form name="passwd" action="protect.php" method="post">
<input type="hidden" name="HTAccessPath" value="<?php print $HTAccessPath; ?>">
<input type="hidden" name="URL" value="<?php print $URL; ?>">
<input type="hidden" name="PasswordPath" value="<?php print $PasswordPath; ?>">
<input type="hidden" name="ActualPasswordPath" value="<?php print $ActualPasswordPath; ?>">
<input type="hidden" name="Path" value="<?php print $_REQUEST["Path"]; ?>">
<h3>Settings</h3>
<table border="0" cellpadding="0" cellspacing="5" width="85%">
<tr>
<td width="25%">
	Enable password protection: 
</td>
<td>
	<input name="Status" type="checkbox" <?php $DirectoryProtected? print " checked ": print ""; ?>>
</td>
</tr>
<tr>
<td>
	Protected directory title: 
</td>
<td>
	<input type="text" style="width:200px; height:20px; border: 1px solid black;" name="Title" value="<?php print $Title; ?>">
</td>
</tr>

<tr>
<td>
	&nbsp;
</td>
<td>
	<input type="submit" id="button" value="Save settings">
</td>
</tr>

</table>
</form>

<p><hr style="border: 1px solid black;"><p>

<?php

if( ($PasswordPath != $ActualPasswordPath) && ($ActualPasswordPath != "") )
{
	print "The password file is not in a place I can use... Please hit the \"Save settings\" button above to fix this, then you will be able to manage users.<p>";
}
else if( ! file_exists($PasswordPath) )
{
	print "The password file does not exist. Enable protection above before you can manage users.<p>";
}
else
{
?>

<form name="Users" method="post" action="edituser.php">
<input type="hidden" name="URL" value="<?php print $URL; ?>">
<input type="hidden" name="PasswordPath" value="<?php print $PasswordPath; ?>">
<input type="hidden" name="Path" value="<?php print $_REQUEST["Path"]; ?>">
<h3>Users</h3>
<table border="0" cellpadding="0" cellspacing="5" width="85%">
<tr>
	<td width="25%">
		User name:
	</td>
	<td width="*">
		<input type="text" style="width:200px; height:20px; border: 1px solid black;" name="UserName">
	</td>
</tr>

<tr>
	<td>
		Password:
	</td>
	<td>
		<input type="password" style="width:200px; height:20px; border: 1px solid black;" name="Password">
	</td>
</tr>

<tr>
	<td>	
		Confirm Password:
	</td>
	<td>
		<input type="password" style="width:200px; height:20px; border: 1px solid black;" name="ConfirmPassword">
	</td>
</tr>

<tr>
	<td>
		&nbsp;
	</td>
	<td>
		<input type="submit" value="Add / Edit user" id="button" onclick="return ValidateAdd(); return false;">
	</td>
</tr>
</table>
</form>


<p><hr style="border: 1px solid black;"><p>


<h3>Existing Users</h3>
<form name="ExistingUsers" action="deleteuser.php" method="post">
<input type="hidden" name="URL" value="<?php print $URL; ?>">
<input type="hidden" name="PasswordPath" value="<?php print $PasswordPath; ?>">
<input type="hidden" name="Path" value="<?php print $_REQUEST["Path"]; ?>">

<table border="0" cellpadding="0" cellspacing="5" width="85%">
<tr>
<td style="width:260px;">
	<select name="UserName" size="10" style="width:250px; border: 1px solid black;"">

	<?php
	for($x = 0; $x < count($UserArray); $x++)
	{

                while( substr($UserArray[$x], strlen($UserArray[$x]) - 1, 1) == '\n')
                {
                        $UserArray[$x] = substr($UserArray, 0, strlen($UserArray) - 1);
                }
	
		if(strlen($UserArray[$x]) > 0)
		{
			$UserArray[$x] = substr($UserArray[$x], 0, strpos($UserArray[$x], ":"));
			print "<option value=\"".$UserArray[$x]."\">".$UserArray[$x]."</option>";
		}
	}
	?>

	</select>
</td>
<td width="*">
	<input type="submit" id="button" value="Delete user" onclick="return ValidateDelete(document.ExistingUsers.UserName.value); return false;">
</td>
</tr>
</table>
</form>

<?php
}
?>

<p>
</div>
</div>

</body>
</html>
