<?php
session_start();
require_once("../includes/functions.inc.php");

ClearLogin();

header("location: ./login.php");
?>
