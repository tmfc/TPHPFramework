<?php

//获取图片高度宽度
function GetImageWidthHeight($filepath, $mimetype)
{
	$img = GetImageFromFile($filepath, $mimetype);
	$width = imagesx($img);
	$height = imagesy($img);

	imagedestroy($img);
	return array($width, $height);
}

//打开图片文件
function GetImageFromFile($filepath, $mimetype)
{
	if(strstr($mimetype,'jpeg'))
	{
		return imagecreatefromjpeg($filepath);
	}
	elseif(strstr($mimetype,'gif'))
	{
		return imagecreatefromgif($filepath);
	}
	elseif(strstr($mimetype,'png'))
	{
		return imagecreatefrompng($filepath);
	}
}


//保存预览图
function SaveThumbnail($filepath, $mimetype, $size)
{
	$img = GetImageFromFile($filepath, $mimetype);

	$width = imagesx($img);
	$height = imagesy($img);
	$thumbWidth;
	$thumbHeight;
		
	if($width > $height)
	{
		$thumbWidth = $size;
		$thumbHeight = $height / $width * $size;
	}
	else
	{
		$thumbHeight = $size;
		$thumbWidth = $width / $height * $size;
	}

	if($thumbWidth < $width && $thumbHeight < $height)
	{
	    $newimg = imagecreatetruecolor($thumbWidth, $thumbHeight);
	    imagecopyresampled($newimg, $img, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $width, $height);
	    ImageJpeg ($newimg, $filepath . '.thumbnail' ,98);
	    chmod($filepath . '.thumbnail', 0777);
	}
	else 
	{
	    ImageJpeg ($img, $filepath . '.thumbnail' ,98);
	    chmod($filepath . '.thumbnail', 0777);
	}
	
	imagedestroy($img);
	imagedestroy($newimg);
}

?>