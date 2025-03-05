#!/usr/bin/php
<?php

$json_sw = '{
    "10.10.18.1": "mikrotik",
    "10.10.17.1": "mikrotik",
    "10.10.13.1": "mikrotik",
    "10.10.18.98": "mikrotik",
    "10.10.19.8": "mikrotik",
    "10.10.15.193": "mikrotik",
    "10.10.14.161": "mikrotik",
    "10.10.16.1": "mikrotik",
    "10.10.14.1": "mikrotik",
    "10.10.18.11":"cisco",
    "10.10.18.12":"cisco",
    "10.10.18.13":"cisco"
}';


$src='';
$stdin = fopen('php://stdin', 'r');
    while (!feof($stdin)) {
	$src .= fgets($stdin);
        }
    fclose($stdin);
    $data=explode("\n",$src);
    $var=$data;
    $array_var = array();
    preg_match_all("/\[(.*?)\]/",$var[1],$matches);
    $ip = $matches[1][0];
    $cont_st=2;
    $cont_end = count($var);
    array_push ($array_var, $ip);
    array_push ($array_var, (date("Y-m-d H:i:s")));
	for ($i = $cont_st ; $i <= $cont_end-1; $i++){
    	    $pos = strpos($var[$i], " ");
    	    echo "\n-->".$pos."<--";
    	    $rest = substr($var[$i], $pos );
    	    echo "\n-->".$rest."<---";
    	    $rest = trim (preg_replace( "/\"$/", '', $rest )); // - убирает финишную кавычку в случае ее наличия
    	    $rest = trim (preg_replace( "/^\"/", '', $rest )); //- убирает лидирующую кавычку в случае ее наличия
    	    echo "\n--2222>".$rest."<---";
    	    array_push ($array_var, $rest);
    	    echo "\n";
	}


//file_put_contents('/var/www/html/_'.$array_var[0].'_.txt', "\n--!!--\n".print_r($array_var,1)."\n--------\n", FILE_APPEND);

// обработаем  инттерфейсы.
$arr_sw = (json_decode ($json_sw , true));
$today = date("Y-m-d H:i:s");

if (($array_var[3] == ".1.3.6.1.6.3.1.1.5.3") || ($array_var[3] == ".1.3.6.1.6.3.1.1.5.4")) { // обработаем трап  от интерфеса.
    //file_put_contents('/root/log_dfl/M1.txt', "\n--!!--\n".print_r($array_var,1)."\n--------\n", FILE_APPEND);// запишем все :-)
    foreach ( $arr_sw as $key => $dev){
	echo " $key ===> $dev\n" ;
	    if (($array_var[0] == $key ) && ($dev ==  "mikrotik" )) {
	    $if_name  = snmpget($array_var[0], "public", sprintf(".1.3.6.1.2.1.2.2.1.2.%s", $array_var[5] ));
	    $if_name  = preg_replace ('/STRING: /', '', $if_name  );
	     if ($if_name  == "internet") {
	     $nik="@Apr , @noe";
	     }else{
	     $nik="";
	     }



	    $mes = "$today\n$nik\nНа!!!!!устройстве: <b>". $array_var[0]."</b>\nИнтерфейс: <b>".$if_name."</b>\nСостояние: <b>".$array_var[7]."</b>";
        }
    
	if (($array_var[0] == $key ) && ($dev ==  "cisco" )) {
	    $if_name  = snmpget($array_var[0], "public", sprintf(".1.3.6.1.2.1.2.2.1.2.%s", $array_var[4] ));
	    $if_name  = preg_replace ('/STRING: /', '', $if_name  );
	    if ($if_name  == "internet") {
		    $nik="@Apr , @noe";
	    }else{
	    	    $nik="";
         }
	
	    
	    $mes = "$today\n$nik\nНа!!!!устройстве: <b>". $array_var[0]."</b>\nИнтерфейс: <b>".$if_name."</b>\nСостояние: <b>".$array_var[7]."</b>";
	}
    
	if (($array_var[0] == $key ) && ($dev ==  "dlink" )) {
	    $if_name  = snmpget($array_var[0], "public", sprintf(".1.3.6.1.2.1.2.2.1.2.%s", $array_var[4] ));
	    $if_name  = preg_replace ('/STRING: /', '', $if_name  );
	    $mes = "$today\nНа устройстве: <b>". $array_var[0]."</b>\nИнтерфейс: <b>".$if_name." ".$array_var[4]."</b>\nСостояние: <b>".$array_var[6]."</b>";
	}
    }

   file_get_contents (sprintf("http://IP_doc/not/not.php?message=%s",rawurlencode($mes))); //<---------------------------
//.print_r($array_var,1)



}

//file_put_contents("/var/www/html/not/alert/al.txt","\n-!!-".$mes."--\n", FILE_APPEND);// запишем все :-)

//file_put_contents("/var/www/html/not/alert/12_.txt", "\n--!!--\n".print_r($array_var,1)."\n--------\n", FILE_APPEND);


    if ($array_var[3] == ".1.3.6.1.4.1.318.0.5"){
	    $mes = "$today\n@apr, @noe\n<b>!!!Отключили токи!!!</b>";
	    file_get_contents (sprintf("http://127.0.0.1/not/not.php?message=%s",rawurlencode($mes)));
    }

    if ($array_var[3] == ".1.3.6.1.4.1.318.0.9"){
	$mes = "$today\n@apr , @noe\n<b>Включили токи!!!!</b>";
	file_get_contents (sprintf("http://127.0.0.1/not/not.php?message=%s",rawurlencode($mes)));
    }

?>
