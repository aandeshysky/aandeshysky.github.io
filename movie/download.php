<?php
  $ServName=iconv("utf-8", "big5", $_GET['ServName']);
  $LocalName=iconv("utf-8", "big5", $_GET['LocalName']);   
  if (!file_exists($ServName))
  {
     header('Content-type: text/html; charset=utf-8');
	 die($ServName. " 檔案不存在"); 
  }  
  header('Content-type: application/force-download');
  header('Content-Transfer-Encoding:Binary');
  header('Content-Disposition: attachment; filename='. $LocalName);  
  readfile($ServName);
?>
