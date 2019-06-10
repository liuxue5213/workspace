<?php
$filename = "document.txt";
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=' . $filename);
print "Hello!";
?>