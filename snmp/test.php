<?php

$mes ="test_cli";
file_put_contents("/var/www/html/not/alert/test.txt","\n-!!-".$mes."--\n", FILE_APPEND);

?>