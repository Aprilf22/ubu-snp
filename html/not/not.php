<?php

$directory_a = __DIR__.'/alert';
$message = $_GET['message'];
file_put_contents($directory_a.'/'.strtotime (date("Y-m-d H:i:s")), "\n".$message."\n", FILE_APPEND);// запишем все :-)

?>
