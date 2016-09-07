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

$colname_rs_country = "-1";
if (isset($_POST['countryID'])) {
  $colname_rs_country = $_POST['countryID'];
}
mysql_select_db($database_dbconn_movie, $dbconn_movie);
$query_rs_country = sprintf("SELECT * FROM country WHERE ID = %s", GetSQLValueString($colname_rs_country, "int"));
$rs_country = mysql_query($query_rs_country, $dbconn_movie) or die(mysql_error());
$row_rs_country = mysql_fetch_assoc($rs_country);
$totalRows_rs_country = mysql_num_rows($rs_country);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>上載檔案報告</title>
</head>
<?php
include ("resize.php");
?>
<body>
<table width="350" border="1" align="center">
  <tr>
    <td width="97">檔案名稱</td>
    <td width="237"> <?php echo $_FILES['uploadfile']['name']; ?></td>
  </tr>
  <tr>
    <td>電影名稱</td>
    <td><?php echo $_POST['movie_name']; ?></td>
  </tr>
  <tr>
    <td>電影種類</td>
    <td><?php echo $_POST['movie_category']; ?></td>
  </tr>
  <tr>
    <td>電影產地</td>
    <td><?php echo $row_rs_country['Name']; ?></td>
  </tr>
  <tr>
    <td>電影主演</td>
    <td><?php echo $_POST['movie_name']; ?></td>
  </tr>
  <tr>
    <td>電影導演</td>
    <td><?php echo $_POST['movie_director']; ?></td>
  </tr>
  <tr>
    <td>上映日期</td>
    <td><?php echo $_POST['movie_date']; ?></td>
  </tr>
  <tr>
    <td>電影片長</td>
    <td><?php echo $_POST['movie_time']; ?></td>
  </tr>
  <tr>
    <td>檔案大小</td>
    <td><?php echo $_FILES['uploadfile']['size'] . " Bytes"; ?></td>
  </tr>
  <tr>
    <td>檔案格式</td>
    <td><?php echo $_FILES['uploadfile']['type']; ?></td>
  </tr>
  <tr>
    <td>暫存檔名稱</td>
    <td><?php echo $_FILES['uploadfile']['tmp_name']; ?></td>
  </tr>
  <tr>
    <td>錯誤代碼</td>
    <td><?php
      // 1. 先判斷上傳至網站的暫存目錄是否有錯誤 
     if ($_FILES['uploadfile']['error']>0)
      {
        switch ($_FILES['uploadfile']["error"])
        {
          case 1: die('ErrCode: 1 檔案大小超出 php.ini:upload_max_filesize 限制'); break;
          case 2: die('ErrCode: 2 檔案大小超出 max_file_size 限制'); break;
          case 3: die('ErrCode: 3 檔案僅被部份上傳,上傳不完整');  break;
          case 4: die('ErrCode: 4 檔案未被上傳'); break;
          case 6: die('ErrCode: 6 暫存目錄不存在');  break;
          case 7: die('ErrCode: 7 無法寫入到檔案'); break;
          case 8: die('ErrCode: 8 上傳停止'); break;
        }
      }
      // 2. 前面 1. 成功, 再將上傳至網站的暫存檔案搬至到另個目錄, 並存成不同檔名
      if(is_uploaded_file($_FILES['uploadfile']['tmp_name']))
      {
		$chkImg=getimagesize($_FILES['uploadfile']['tmp_name']);
		if (!$chkImg)
		 die("不是圖檔");
		
		$DestDir ="files";
        if(!is_dir($DestDir) || !is_writeable($DestDir))
          die("目錄不存在或無法寫入");
        $tmp_filename=$_FILES['uploadfile']['tmp_name'];
        $originalfilename = $_FILES['uploadfile']['name']; 
        $Server_filename = $DestDir. "/". date("YmdHis") . "-" . $originalfilename; 
		$thumb_filename= "thumbnail/" . date("YmdHis") . "-" . $originalfilename;
        if (move_uploaded_file($tmp_filename, iconv("utf-8","big5", $Server_filename)))
          {
		   echo $originalfilename ." 檔案上傳成功";
		   $srcfile=iconv("utf-8", "big5", $Server_filename);
		   $destfile=iconv("utf-8", "big5", $thumb_filename) ;
		   imageResize($srcfile, $destfile, 160);
		   require_once('Connections/dbconn_movie.php'); 
		  $insertSQL = sprintf("INSERT INTO movie (LocalName, ServName, ThumbName, FileSize, 
			 FileType, MovieName, Category, Actor, Director, Date, Time, CountryID) VALUES (%s,%s,%s,%s,%s,%s,%s
			 ,%s,%s,%s,%s,%s)",
		   GetSQLValueString($originalfilename, "text"),
		   GetSQLValueString($Server_filename, "text"),
		   GetSQLValueString($thumb_filename, "text"),
		   GetSQLValueString($_FILES['uploadfile']['size'], "text"),
		   GetSQLValueString($_FILES['uploadfile']['type'], "text"),
		   GetSQLValueString($_POST['movie_name'], "text"),
		   GetSQLValueString($_POST['movie_category'], "text"),
		   GetSQLValueString($_POST['movie_actor'], "text"),
		   GetSQLValueString($_POST['movie_director'], "text"),
		   GetSQLValueString($_POST['movie_date'], "text"),
		   GetSQLValueString($_POST['movie_time'], "text"),
		   GetSQLValueString($_POST['countryID'], "text"));   			                               
		   mysql_select_db($database_dbconn_movie, $dbconn_movie);
		  $Result1 = mysql_query($insertSQL, $dbconn_movie) or die(mysql_error());
		} 
       
	    else
          die("檔案上傳失敗");        
      }
      ?></td>
  </tr>
</table>
<p align="center">&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rs_country);
?>
