<?
namespace Library\Extension;

/**
* this class resizes images
*/
trait Image{

	var $watermarkPos = 'center';//topleft,topmiddle,topright,middleleft,middleright,bottomleft,bottomcenter,bottomright

	/**
	* resize or crop image
	* @param String $file AS image file with full location
	* @param String $imageName AS new image name
	* @param String $width AS new width
	* @param String $height AS new height
	* @param String $locationPath AS new location
	* @param Boolean $resize=true AS crop
	* @param Boolean $watermark=false AS set watermark
	* @param String $watermarkText='' AS Watermark text
	* @param String $font_size='' AS Watermark font size
	* @param String $font='' AS Watermark font name font location is 'templates/templatename/fonts/'
	* @param Array $color=array() AS Watermark RGB color
	*/

	function _resize($file, $width, $height, $locationPath){

		$file = $this->_copyImage($file, basename($file), $locationPath);
		$this->_imageResize($file, $width, $height);
		return $file;
	}

	function _crop($file, $width, $height, $locationPath){

		$file = $this->_copyImage($file, basename($file), $locationPath);
		$this->_imageCrop($file, $width, $height);
		return $file;
	}

	function _watermark($file, $watermarkFile, $watermarkX, $watermarkY){

		return $this->createWatermark($file, $watermarkFile, $watermarkX, $watermarkY);
	}

	private function _copyImage($file, $imageName, $locationPath){

		if( !is_Dir($locationPath) )
			mkdir($locationPath, 0755, true);

		copy($file, $locationPath . DIRECTORY_SEPARATOR . $imageName);
		return $locationPath . DIRECTORY_SEPARATOR . $imageName;
	}

	private function _imageResize( $file, $width, $height ){

		$extension = pathinfo($file, PATHINFO_EXTENSION);
		if( strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' ){

			$this->resizeJPGImage($file, $width, $height);
		}
		if( strtolower($extension) == 'png' ){

			$this->resizePNGImage($file, $width, $height);
		}
		if( strtolower($extension) == 'gif' ){

			$this->resizeGIFImage($file, $width, $height);
		}
	}

	private function _imageCrop( $file, $width, $height ){

		$extension = pathinfo($file, PATHINFO_EXTENSION);
		if( strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' ){

			$this->cropJPGImage($file, $width, $height);
		}
		if( strtolower($extension) == 'png' ){

			$this->cropPNGImage($file, $width, $height);
		}
		if( strtolower($extension) == 'gif' ){

			$this->cropGIFImage($file, $width, $height);
		}
	}

	private function calculateDimensions($origWidth, $origHeight, $maxWidth, $maxHeight){

        if( $maxWidth > $maxHeight ){

			$ratio = $maxWidth * 100 / $origWidth;
		}
		else{

			$ratio = $maxHeight * 100 / $origHeight;
		}

		$newWidth = $origWidth * $ratio / 100;
		$newHeight = $origHeight * $ratio / 100;

        return array((int)$newWidth, (int)$newHeight);
    }

	private function calculateCenter($origWidth, $origHeight, $maxWidth, $maxHeight){

		$x = ($origWidth - $maxWidth) / 2;
		$y = ($origHeight - $maxHeight) / 2;

		return array($x, $y);
	}

	//JPG
	private function resizeJPGImage ($file, $width, $height){

		$qua = 100; //kvaliteet
		list($origWidth, $origHeight) = getimagesize($file);
		list($width, $height) = $this->calculateDimensions($origWidth, $origHeight, $width, $height);
		$sizebeseem = ( ($origWidth + $origHeight) <= ($width + $height) ) ? false : true;

		$image_p = @imagecreatetruecolor($width, $height) or ($sizebeseem = false);
		if($sizebeseem == true){

			$image = imagecreatefromjpeg($file);
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $origWidth, $origHeight);
			@imagejpeg ($image_p,$file,$qua);
			imagedestroy($image_p);
		}
	}
	
	private function cropJPGImage ($file, $width, $height){

		$this->resizeJPGImage($file, $width + 20, $height + 20);
		list($origWidth, $origHeight) = getimagesize($file);
		list($x, $y) = $this->calculateCenter($origWidth, $origHeight, $width, $height);
		$sizebeseem = ( ($origWidth + $origHeight) <= ($width + $height) ) ? false : true;

		if($sizebeseem == true){

			$im = imagecreatefromjpeg($file);
			$image_p = imagecrop($im, ['x' => $x, 'y' => $y, 'width' => $width, 'height' => $height]);
			if ($image_p !== FALSE)
				imagejpeg($image_p, $file);
			imagedestroy($image_p);
		}
	}

	//GIF
	private function resizeGIFImage ($file, $width, $height){

		list($origWidth, $origHeight) = getimagesize($file);
		list($width, $height) = $this->calculateDimensions($origWidth, $origHeight, $width, $height);
		$sizebeseem = ( ($origWidth + $origHeight) <= ($width + $height) ) ? false : true;

		$image_p = @imagecreatetruecolor($width, $height) or ($sizebeseem = false);
		if($sizebeseem == true){

			$image = imagecreatefromgif($file);
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $origWidth, $origHeight);
			@imagegif ($image_p, $file);
		}
	}
	
	private function cropGIFImage ($file, $width, $height){

		$this->resizeJPGImage($file, $width + 20, $height + 20);
		list($origWidth, $origHeight) = getimagesize($file);
		list($x, $y) = $this->calculateCenter($origWidth, $origHeight, $width, $height);
		$sizebeseem = ( ($origWidth + $origHeight) <= ($width + $height) ) ? false : true;

		if($sizebeseem == true){

			$im = imagecreatefromgif($file);
			$image_p = imagecrop($im, ['x' => $x, 'y' => $y, 'width' => $width, 'height' => $height]);
			if ($image_p !== FALSE)
				imagegif($image_p, $file);
			imagedestroy($image_p);
		}
	}


	//PNG
	private function resizePNGImage ($file, $width, $height){

		list($origWidth, $origHeight) = getimagesize($file);
		list($width, $height) = $this->calculateDimensions($origWidth, $origHeight, $width, $height);
		$sizebeseem = ( ($origWidth + $origHeight) <= ($width + $height) ) ? false : true;

		$image_p = @imagecreatetruecolor($width, $height) or ($sizebeseem = false);
		if($sizebeseem == true){

			$image = imagecreatefrompng($file);
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $origWidth, $origHeight);
			@imagepng ($image_p, $file);
		}
		imagedestroy($image_p);
		return $sizebeseem;
	}
	
	private function cropPNGImage ($file,$width, $height){

		$this->resizeJPGImage($file, $width + 20, $height + 20);
		list($origWidth, $origHeight) = getimagesize($file);
		list($x, $y) = $this->calculateCenter($origWidth, $origHeight, $width, $height);
		$sizebeseem = ( ($origWidth + $origHeight) <= ($width + $height) ) ? false : true;

		if($sizebeseem == true){

			$im = imagecreatefrompng($file);
			$image_p = imagecrop($im, ['x' => $x, 'y' => $y, 'width' => $width, 'height' => $height]);
			if ($image_p !== FALSE)
				@imagepng($image_p, $file);
			imagedestroy($image_p);
		}
	}

	private function watermarkSource($watermarkFile){

		$extension = pathinfo($watermarkFile, PATHINFO_EXTENSION);
		if( strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' ){

			return imagecreatefromjpeg($watermarkFile);
		}
		if( strtolower($extension) == 'png' ){

			return imagecreatefrompng($watermarkFile);
		}
		if( strtolower($extension) == 'gif' ){

			return imagecreatefromgif($watermarkFile);
		}
	}

	private function createWatermark($file, $watermarkFile, $watermarkX, $watermarkY){

		$watermark = $this->watermarkSource($watermarkFile);
		list($watermark_width, $watermark_height) = getimagesize($watermarkFile);

		$extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
		if( $extension == 'jpg' || $extension == 'jpeg' ){

			$image_p = imagecreatefromjpeg($file);
			imagecopy($image_p, $watermark, $watermarkX, $watermarkY, 0, 0, $watermark_width, $watermark_height);
			imagejpeg ($image_p, $file);
		}
		if( $extension == 'png' ){

			$image_p = imagecreatefromjpeg($file);
			imagecopy($image_p, $watermark, $watermarkX, $watermarkY, 0, 0, $watermark_width, $watermark_height);
			imagejpeg ($image_p, $file);
		}
		if( $extension == 'jpg' ){

			$image_p = imagecreatefromjpeg($file);
			imagecopy($image_p, $watermark, $watermarkX, $watermarkY, 0, 0, $watermark_width, $watermark_height);
			imagejpeg ($image_p, $file);
		}

		imagedestroy($image_p);
		imagedestroy($watermark);
	}
}
?>