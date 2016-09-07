<?php 
  function imageResize($srcfile, $destfile, $MaxLen)
  {
    if(file_exists($srcfile) && isset($destfile))
	  {
      // 1. 取得來源圖檔資訊
      $srcSize=getimagesize($srcfile); 
      $srcW=$srcSize[0];
      $srcH=$srcSize[1];	
      $srcExtension=$srcSize[2];
      // 2. 計算調整後的寬度與高度
      $srcRatio=$srcW/$srcH; 		
      if($srcRatio>1)  
      {
        $destW=$MaxLen;
        $destH=$destW/$srcRatio;   // 寬>高
      }
      else
	    {
	      $destH=$MaxLen;            // 高>=寬 
	      $destW=$destH*$srcRatio;	 
	    }  	 	 
	  }
    // 3. 建立用來存放目的影像的記憶體空間
    $destImage=imagecreatetruecolor($destW, $destH);
    // 4. 建立用來存放來源影像的記憶體空間
    switch ($srcExtension)
    {  
	    case 1: $srcImage=imagecreatefromgif($srcfile);  break;
	    case 2: $srcImage=imagecreatefromjpeg($srcfile); break;
	    case 3: $srcImage=imagecreatefrompng($srcfile);  break;  
    }
    // 5. 從來源影像的記憶體內容將影像依指定長寬按比例複製到目的影像的記憶體內容
    imagecopyresampled($destImage,$srcImage, 0,0,0,0, $destW, $destH, imagesx($srcImage), imagesy($srcImage));
    // 6. 將目的影像的記憶體內容寫到目的圖檔
    switch($srcExtension)
    { 
	    case 1:imagegif($destImage, $destfile); break;
	    case 2:imagejpeg($destImage, $destfile, 85); break;
	    case 3:imagepng($destImage, $destfile); break; 
    }
  }
?>

