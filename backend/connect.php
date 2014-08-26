<?php

function connect($db_name){
	$mysql_host = 'localhost';
	$mysql_user = 'root';
	$mysql_pass = '';
	
	$link = mysqli_connect($mysql_host,$mysql_user,$mysql_pass,$db_name);
	
	if(!$link){
		die('Error 101:Connection to the host cannot be established');
	}else{
		echo mysqli_error($link);
	}
	
	return $link;
}

?>