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

mysql_select_db($database_dbconn_movie, $dbconn_movie);
$query_rs_country = "SELECT * FROM country";
$rs_country = mysql_query($query_rs_country, $dbconn_movie) or die(mysql_error());
$row_rs_country = mysql_fetch_assoc($rs_country);
$totalRows_rs_country = mysql_num_rows($rs_country);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>選擇上載檔案</title>
</head>

<body>
<form action="upload.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <p align="center">請選擇欲上傳之海報檔案:
    <input name="MAX_FILE_SIZE" type="hidden" id="MAX_FILE_SIZE" value="3000000" />
    <label>
      <input type="file" name="uploadfile" id="uploadfile" />
    </label>
    <label>
      <input type="submit" name="button" id="button" value="送出" />
    </label>
  </p>
  <p align="center">電影名稱:
    <label> </label>
    <label>
      <input type="text" name="movie_name" id="movie_name" />
    </label>
  </p>
  <p align="center">電影種類:
    <label> </label>
    <label>
      <input type="text" name="movie_category" id="movie_category" />
    </label>
  </p>
   <p align="center">電影主演:
     <label> </label>
    <label>
      <input type="text" name="movie_actor" id="movie_actor" />
    </label>
  </p>
   <p align="center">電影導演:
    <label> </label>
    <label>
      <input type="text" name="movie_director" id="movie_director" />
    </label>
  </p>
   <p align="center">上映日期:
    <label> </label>
    <label>
      <input type="text" name="movie_date" id="movie_date" />
    </label>
  </p>
   <p align="center">電影片長:
    <label> </label>
    <label>
      <input type="text" name="movie_time" id="movie_time" />
    </label>
  </p>
  <p align="center">電影產地:
    <label for="countryID"></label>
    <select name="countryID" id="countryID">
      <?php
do {  
?>
      <option value="<?php echo $row_rs_country['ID']?>"><?php echo $row_rs_country['Name']?></option>
      <?php
} while ($row_rs_country = mysql_fetch_assoc($rs_country));
  $rows = mysql_num_rows($rs_country);
  if($rows > 0) {
      mysql_data_seek($rs_country, 0);
	  $row_rs_country = mysql_fetch_assoc($rs_country);
  }
?>
    </select>
  </p>
</form>
</body>
</html>
<?php
mysql_free_result($rs_country);
?>
