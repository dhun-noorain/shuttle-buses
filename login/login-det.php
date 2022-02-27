<?php
$db_host = 'localhost';
$db_un   = 'id18456326_noorain';
$db_pw   = '0Z%-w(yQM%RS5Kcp';
$db_name = 'id18456326_shuttle_buses';

$db_con = new mysqli($db_host, $db_un, $db_pw, $db_name);
if($db_con->connect_error) die("Unable to connect" . $db_con->connect_error);
?>