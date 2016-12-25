<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/includes/functions.inc");

$FileName = $_GET["FileName"];

$x = DeleteFile($FileName);

if($x == 1)
{
	print "deleted";
}
else if($x == -1)
{
	print "That file does not exist";
}
else
{
	print "Cannot delete file (please check permissions)";
}

?>
