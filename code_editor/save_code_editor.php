<?php

$fp = fopen($_POST["FileName"], "w");
fwrite($fp, trim($_POST["FileContents"]));
fclose($fp);

print "saved";
?>
