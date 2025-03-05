#!/usr/bin/php
<?php


/*

$bot_id="303480220:AAGKaySS73dE1IqaM7kmAWHA2MVKhEiEFeQ";
$mes = "ПривеДД <b>444444444!</b> ";
message_telegram ($bot_id, $mes);
*/

function message_telegram ($bot_id, $mes){
$counter_arr =1;
    $mes= rawurlencode($mes);
    $users = json_decode(file_get_contents(__DIR__.'/user1.json'));
	foreach ($users as $user_num => $user) {
	    echo "send $user\n";

		while ($counter_arr <=2 ){

		    $Peremenaya=sprintf("https://api.telegram.org/bot%s/sendMessage?disable_web_page_preview=true&chat_id=%s&text=%s&parse_mode=html",$bot_id, $user, $mes);
		    echo $Peremenaya;
		    //file_put_contents(__DIR__."/--new1.txt","\n-!!-\n".$Peremenaya."\n--\n", FILE_APPEND);
		    $ch = curl_init();
		    curl_setopt($ch, CURLOPT_URL, "$Peremenaya");
		    //curl_setopt($ch, CURLOPT_IPRESOLVE);
		    //curl_setopt($ch, CURL_IPRESOLVE_V4);
		    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
		    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		    curl_setopt($ch, CURLOPT_HEADER, 0);
			if(($result = curl_exec($ch)) !== false) {
			    echo "\nсообщение отправлено\n";
        		    $update = json_decode($result, TRUE);
        		    print_r ($update);
        		    echo "--------".$update['ok']."----------\n";
        		    $counter_arr = 3;
			}else{
			    echo "\nOшибка: ".curl_error($ch);
			    file_put_contents(__DIR__."/--new1.txt","\n-!!-\n".curl_error($ch)."\n--\n", FILE_APPEND);
			}
		}
	$counter_arr =1;
	}
}

?>
