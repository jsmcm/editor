<?php
session_start();
require_once("../config.inc.php");
require_once("../includes/functions.inc.php");

ClearLogin();

$UserEmailAddress = filter_var($_POST["EmailAddress"], FILTER_SANITIZE_EMAIL);
$UserPassword = filter_var($_POST["Password"], FILTER_SANITIZE_STRING);

if($EmailAddress != $UserEmailAddress)
{
	header("Location: index.php?Notes=Login failed, please retry");
	exit();
}

if($Password != $UserPassword)
{
	header("Location: index.php?Notes=Login failed, please retry");
	exit();
}

SetLogin($UserEmailAddress);
header("location: ../index.php");
?>
