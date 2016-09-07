<?php require_once('Connections/dbconn_movie.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

if ((isset($_GET['ID'])) && ($_GET['ID'] != "")) {
  $deleteSQL = sprintf("DELETE FROM movie WHERE ID=%s",
                       GetSQLValueString($_GET['ID'], "int"));

  mysql_select_db($database_dbconn_movie, $dbconn_movie);
  $Result1 = mysql_query($deleteSQL, $dbconn_movie) or die(mysql_error());

  //刪除檔案
   $ServName=iconv("utf-8", "big5", $_GET['ServName']);
  if(file_exists($ServName))
    unlink($ServName); 
  $ThumbName=iconv("utf-8", "big5", $_GET['ThumbName']);
  if(file_exists($ThumbName))
    unlink($ThumbName);
	
  $deleteGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

mysql_select_db($database_dbconn_movie, $dbconn_movie);
$query_rs_movie = "SELECT * FROM movie";
$rs_movie = mysql_query($query_rs_movie, $dbconn_movie) or die(mysql_error());
$row_rs_movie = mysql_fetch_assoc($rs_movie);
$totalRows_rs_movie = mysql_num_rows($rs_movie);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>無標題文件</title>
</head>

<body>
</body>
</html>
<?php
mysql_free_result($rs_movie);
?>
