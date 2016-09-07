<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_dbconn_movie = "localhost";
$database_dbconn_movie = "movie";
$username_dbconn_movie = "root";
$password_dbconn_movie = "123456";
$dbconn_movie = mysql_pconnect($hostname_dbconn_movie, $username_dbconn_movie, $password_dbconn_movie) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_query ("SET NAMES 'UTF8'");
?>