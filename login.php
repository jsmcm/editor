<?php

session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/includes/functions.inc");

$ConnectingHost = $_SERVER["HTTP_REFERER"];

if(strpos($ConnectingHost, "http://") > -1)
{
	$ConnectingHost = substr($ConnectingHost,7);
}

$x = strpos($ConnectingHost, ":");

if($x > -1)
{
	$ConnectingHost = substr($ConnectingHost,0, $x);
}

$x = strpos($ConnectingHost, "/");

if($x > -1)
{
	$ConnectingHost = substr($ConnectingHost,0, $x);
}

$OwnIP = gethostbyname($_SERVER["SERVER_NAME"]);
$ConnectingIP = gethostbyname($ConnectingHost);

if($OwnIP != $ConnectingIP)
{
	//print "Ip mismatch<p>";
	//goto SkipExit;
	header("location: auth.html");
	exit();
}

if( ! isset($_POST["ServerName"]))
{
	//print "Server name not set by caller<p>";
	//goto SkipExit;
	header("location: auth.html");
	exit();
}

if( $_POST["ServerName"] != $_SERVER["SERVER_NAME"])
{
	//print "Server name mismatch<p>";
	//goto SkipExit;
	header("location: auth.html");
	exit();
}

SetLogin($_POST["ServerName"]);
header("location: index.php");
exit();

SkipExit:

print "<b><u>Posts:</u></b><br>";
foreach($_POST as $key => $val)
{
	print $key." = ".$val."<br>";
}

print "<p>";


print "<b><u>SERVER:</u></b><br>";
foreach($_SERVER as $key => $val)
{
	print $key." = ".$val."<br>";
}

print "<p>";

print "Connecting IP: ".$ConnectingIP."<br>";
print "Own IP: ".$OwnIP."<br>";
?>
