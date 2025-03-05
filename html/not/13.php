#!/usr/bin/php

<?php

include __DIR__.'/conf/bot_id.php';
include __DIR__.'/send_mes_funk3.php';

$i=0;
$directory_a = __DIR__.'/alert';
$directory_s = __DIR__.'/send';
$smallest_time=INF;
$oldest_file=' ';

while ($i <= 1) {
    $smallest_time=INF;
	if ($handle = opendir($directory_a)) {
	    while (false !== ($file = readdir($handle))) {
		$time=filemtime($directory_a.'/'.$file);
		    if (is_file($directory_a.'/'.$file)) {
			if ($time < $smallest_time) {
			    $oldest_file = $file;
			    $smallest_time = $time;
			}
		    }
	    }
	    closedir($handle);
	}

    if (file_exists($directory_a.'/'.$oldest_file)) {
		echo file_get_contents($directory_a.'/'.$oldest_file)."-----\n";
		$message =  trim (file_get_contents ($directory_a.'/'.$oldest_file));
		$message =  "__".date ("H:i:s Y m d",filemtime($directory_a.'/'.$oldest_file))."__\n".str_replace(chr(10), "\n", 
		$message);
		message_telegram ($bot_id, $message);
		echo "Файл $oldest_file перемещен.";
		$file_for_delete = sprintf( "%s/%s", $directory_a, $oldest_file );
		echo "--->".$file_for_delete."<--\n";
		rename ($directory_a.'/'.$oldest_file, $directory_s.'/'.$oldest_file);
		$oldest_file=' ';
    }
sleep (2);
}
