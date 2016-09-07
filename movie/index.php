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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rs_movie = 10;
$pageNum_rs_movie = 0;
if (isset($_GET['pageNum_rs_movie'])) {
  $pageNum_rs_movie = $_GET['pageNum_rs_movie'];
}
$startRow_rs_movie = $pageNum_rs_movie * $maxRows_rs_movie;

mysql_select_db($database_dbconn_movie, $dbconn_movie);
$query_rs_movie = "SELECT * FROM movie";
$query_limit_rs_movie = sprintf("%s LIMIT %d, %d", $query_rs_movie, $startRow_rs_movie, $maxRows_rs_movie);
$rs_movie = mysql_query($query_limit_rs_movie, $dbconn_movie) or die(mysql_error());
$row_rs_movie = mysql_fetch_assoc($rs_movie);

if (isset($_GET['totalRows_rs_movie'])) {
  $totalRows_rs_movie = $_GET['totalRows_rs_movie'];
} else {
  $all_rs_movie = mysql_query($query_rs_movie);
  $totalRows_rs_movie = mysql_num_rows($all_rs_movie);
}
$totalPages_rs_movie = ceil($totalRows_rs_movie/$maxRows_rs_movie)-1;

$queryString_rs_movie = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rs_movie") == false && 
        stristr($param, "totalRows_rs_movie") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rs_movie = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rs_movie = sprintf("&totalRows_rs_movie=%d%s", $totalRows_rs_movie, $queryString_rs_movie);
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
&nbsp;
<table width="244" border="1" align="center">
  <tr>
    <td width="234"><div align="center">電影海報收藏館<a href="uploadselect.php">上傳海報</a></div></td>
  </tr>
  <tr>
    <td height="116"><table >
      <tr>
        <?php
$rs_movie_endRow = 0;
$rs_movie_columns = 5; // number of columns
$rs_movie_hloopRow1 = 0; // first row flag
do {
    if($rs_movie_endRow == 0  && $rs_movie_hloopRow1++ != 0) echo "<tr>";
   ?>
        <td><table width="200" border="1" align="center">
          <tr>
            <td colspan="2"><div align="center"><a href ="display.php?ID=<?php echo $row_rs_movie['ID']?>>"><img src="<?php echo $row_rs_movie['ThumbName']; ?>"/></div></td>
          </tr>
          <tr>
            <td colspan="2"><div align="center"><?php echo $row_rs_movie['MovieName']; ?></div></td>
          </tr>
          <tr>
            <td width="89"><div align="center"><?php echo $row_rs_movie['FileType']; ?></div></td>
            <td width="95"><div align="center"><?php echo Round($row_rs_movie['FileSize']/1024,0). "K"; ?></div></td>
          </tr>
          <tr>
            <td><div align="center"><a href="delete.php?ID=<?php echo $row_rs_movie['ID']; ?>&amp;ServName=<?php echo $row_rs_movie['ServName']; ?>&amp;ThumbName=<?php echo $row_rs_movie['ThumbName']; ?>">刪除</a></div></td>
            <td><div align="center"><a href="download.php?ServName=<?php echo $row_rs_movie['ServName']; ?>&amp;LocalName=<?php echo $row_rs_movie['LocalName']; ?>">下載</a></div></td>
          </tr>
        </table></td>
        <?php  $rs_movie_endRow++;
if($rs_movie_endRow >= $rs_movie_columns) {
  ?>
      </tr>
      <?php
 $rs_movie_endRow = 0;
  }
} while ($row_rs_movie = mysql_fetch_assoc($rs_movie));
if($rs_movie_endRow != 0) {
while ($rs_movie_endRow < $rs_movie_columns) {
    echo("<td>&nbsp;</td>");
    $rs_movie_endRow++;
}
echo("</tr>");
}?>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;
      <div align="center">
        <table border="0">
          <tr>
            <td><?php if ($pageNum_rs_movie > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_rs_movie=%d%s", $currentPage, 0, $queryString_rs_movie); ?>">第一頁</a>
                <?php } // Show if not first page ?></td>
            <td><?php if ($pageNum_rs_movie > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_rs_movie=%d%s", $currentPage, max(0, $pageNum_rs_movie - 1), $queryString_rs_movie); ?>">上一頁</a>
                <?php } // Show if not first page ?></td>
            <td><?php if ($pageNum_rs_movie < $totalPages_rs_movie) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_rs_movie=%d%s", $currentPage, min($totalPages_rs_movie, $pageNum_rs_movie + 1), $queryString_rs_movie); ?>">下一頁</a>
                <?php } // Show if not last page ?></td>
            <td><?php if ($pageNum_rs_movie < $totalPages_rs_movie) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_rs_movie=%d%s", $currentPage, $totalPages_rs_movie, $queryString_rs_movie); ?>">最後一頁</a>
                <?php } // Show if not last page ?></td>
          </tr>
        </table>
    </div></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($rs_movie);
?>
