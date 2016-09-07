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

$colname_rs_movie = "-1";
if (isset($_GET['ID'])) {
  $colname_rs_movie = $_GET['ID'];
}
mysql_select_db($database_dbconn_movie, $dbconn_movie);
$query_rs_movie = sprintf("SELECT* FROM movie LEFT JOIN country ON movie.CountryID = country.ID WHERE movie.ID = %s", GetSQLValueString($colname_rs_movie, "int"));
$rs_movie = mysql_query($query_rs_movie, $dbconn_movie) or die(mysql_error());
$row_rs_movie = mysql_fetch_assoc($rs_movie);
$totalRows_rs_movie = mysql_num_rows($rs_movie);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>電影海報收藏館</title>
<style type="text/css">
<!--
body {
	background-color: #FC6;
}
-->
</style></head>

<body>
<table width="375" border="1" align="center">
  <tr>
    <td width="365"><div align="center"><img src="<?php echo $row_rs_movie['ServName']; ?>"/></div></td>
  </tr>
  <tr>
    <td>電影名稱:<?php echo $row_rs_movie['MovieName']; ?></td>
  </tr>
  <tr>
    <td>電影主演:<?php echo $row_rs_movie['Actor']; ?></td>
  </tr>
  <tr>
    <td>電影導演:<?php echo $row_rs_movie['Director']; ?></td>
  </tr>
  <tr>
    <td>電影產地:<?php echo $row_rs_movie['Name']; ?></td>
  </tr>
  <tr>
    <td>電影類型:<?php echo $row_rs_movie['Category']; ?></td>
  </tr>
  <tr>
    <td>上映日期:<?php echo $row_rs_movie['Date']; ?></td>
  </tr>
  <tr>
    <td>電影片長:<?php echo $row_rs_movie['Time']; ?></td>
  </tr>
  <tr>
    <td>檔案大小:<?php echo Round($row_rs_movie['FileSize']/1024,0)."K"; ?></td>
  </tr>
  <tr>
    <td>檔案類型:<?php echo $row_rs_movie['Category']; ?></td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rs_movie);
?>
