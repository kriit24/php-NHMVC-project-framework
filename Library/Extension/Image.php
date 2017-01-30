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
	* @param String $img_name AS new image name
	* @param String $width AS new width
	* @param String $height AS new height
	* @param String $location_dir AS new location
	* @param Boolean $resize=true AS crop
	* @param Boolean $watermark=false AS set watermark
	* @param String $watermarkText='' AS Watermark text
	* @param String $font_size='' AS Watermark font size
	* @param String $font='' AS Watermark font name font location is 'templates/templatename/fonts/'
	* @param Array $color=array() AS Watermark RGB color
	*/

	function _resize($file, $width, $height, $location_dir){

		return $this->_imageresize($file, basename($file), $width, $height, $location_dir, false);
	}

	function _crop($file, $width, $height, $location_dir){

		return $this->_imageresize($file, basename($file), $width, $height, $location_dir, true);
	}

	private function _imageresize($file, $img_name, $width, $height, $location_dir, $resize=true, $watermark=false, $watermarkText='', $font_size='', $font='', $color=array(0, 0, 0)){

		/*
		pildi v�hendamine
		file - pilt
		img_name - uus pildi nimi
		width - laius
		height - k�rgus
		kat - kataloog kuhu uus pilt teha
		resize - kas p�rast pildi v�hendamist ka pilt l�igata
		*/
		if( !is_Dir($location_dir) )
			mkdir($location_dir, 0755, true);

		list($width_orig, $height_orig) = getimagesize($file);
		$type = strtolower($this -> ImageType($file));

		if($width_orig < $width || $height_orig < $height){

			copy($file, $location_dir.DIRECTORY_SEPARATOR.$img_name);
			list($width, $height) = getimagesize($file);

			switch ($type) {

				case 'jpg':
					if($watermark){

						$image_p = imagecreatefromjpeg($location_dir.DIRECTORY_SEPARATOR.$img_name);
						if($watermarkText){

							$image_p = $this->WaterMarkText($image_p, $width, $height, $watermarkText, $font_size, $font, $color);
						}
						else{

							$image_p = $this->WaterMark($image_p, $watermark);
						}
						@imagejpeg ($image_p,$location_dir.DIRECTORY_SEPARATOR.$img_name,100);
					}
				break;

				case 'jpeg':
					if($watermark){

						$image_p = imagecreatefromjpeg($location_dir.DIRECTORY_SEPARATOR.$img_name);
						if($watermarkText){

							$image_p = $this->WaterMarkText($image_p, $width, $height, $watermarkText, $font_size, $font, $color);
						}
						else{

							$image_p = $this->WaterMark($image_p, $watermark);
						}
						@imagejpeg ($image_p,$location_dir.DIRECTORY_SEPARATOR.$img_name,100);
					}
				break;

				case 'gif':
					if($watermark){

						$image_p = imagecreatefromgif($location_dir.DIRECTORY_SEPARATOR.$img_name);
						if($watermarkText){

							$image_p = $this->WaterMarkText($image_p, $width, $height, $watermarkText, $font_size, $font, $color);
						}
						else{

							$image_p = $this->WaterMark($image_p, $watermark);
						}
						@imagegif ($temp_img, $location_dir.DIRECTORY_SEPARATOR.$img_name);
					}
				break;

				case 'png':
					if($watermark){

						$image_p = imagecreatefrompng($location_dir.DIRECTORY_SEPARATOR.$img_name);
						if($watermarkText){

							$image_p = $this->WaterMarkText($image_p, $width, $height, $watermarkText, $font_size, $font, $color);
						}
						else{

							$image_p = $this->WaterMark($image_p, $watermark);
						}
						@imagepng ($image_p, $location_dir.DIRECTORY_SEPARATOR.$img_name);
					}
				break;
			}
			return $location_dir.DIRECTORY_SEPARATOR.$img_name;
		}

		switch ($type) {

			case 'jpg':
				$size = $this -> CompressJPGImage ($file, $img_name, $width, $height, $location_dir, $watermark, $watermarkText, $font_size, $font, $color, $resize);
				if($resize == true){

					$this -> ResizeJPGImage($location_dir.DIRECTORY_SEPARATOR.$img_name, $img_name, $width, $height, $location_dir);
				}
			break;

			case 'jpeg':
				$size = $this -> CompressJPGImage ($file, $img_name, $width, $height, $location_dir, $watermark, $watermarkText, $font_size, $font, $color, $resize);
				if($resize == true){

					$this -> ResizeJPGImage($location_dir.DIRECTORY_SEPARATOR.$img_name, $img_name, $width, $height, $location_dir);
				}
			break;

			case 'gif':
				$size = $this -> CompressGIFImage ($file, $img_name, $width, $height, $location_dir, $watermark, $watermarkText, $font_size, $font, $color, $resize);
				if($resize == true){

					$this -> ResizeGIFImage($location_dir.DIRECTORY_SEPARATOR.$img_name, $img_name, $width, $height, $location_dir);
				}
			break;

			case 'png':
				$size = $this -> CompressPNGImage ($file, $img_name, $width, $height, $location_dir, $watermark, $watermarkText, $font_size, $font, $color, $resize);
				if($resize == true){

					$this -> ResizePNGImage($location_dir.DIRECTORY_SEPARATOR.$img_name, $img_name, $width, $height, $location_dir);
				}
			break;
		}
		return $location_dir.DIRECTORY_SEPARATOR.$img_name;
	}

	private function compressSize($new_width, $new_height, $width_orig, $height_orig){

		$resize_to_width = 0;
		$resize_to_height = 0;
		$resize_to = 0;
		$resize_from = $height_orig;

		if($width_orig < $height_orig){

			$resize_from = $width_orig;
		}


		//699 365 OLD WIDTH HEIGHT
		//400 400 NEW WIDTH HEIGHT

		//400 <= 400 && 400 <= 365 && 400 <= 699
		if($new_height <= $new_width && $new_height <= $height_orig && $new_height <= $width_orig){

			$resize_to = $new_height;
		}
		//400 <= 400 && 400 <= 365 && 400 <= 699
		if($new_width <= $new_height && $new_width <= $height_orig && $new_width <= $width_orig){

			$resize_to = $new_width;
		}
		if($resize_to <= 0){

			$resize_to = ($height_orig <= $width_orig ? $height_orig : $width_orig);
		}

		if($new_height == 0){

			$resize_to = $new_width;
		}
		if($new_width == 0){

			$resize_to = $new_height;
		}

		//leiame protsendi, kui palju v�hendame
		$precent = 0;
		if($resize_to < $resize_from){

			//$precent = floor(100-($resize_to*100/$resize_from));
			$precent = 100-($resize_to*100/$resize_from);
			$width = round($width_orig-($width_orig*$precent/100), 0);
			$height = round($height_orig-($height_orig*$precent/100), 0);
		}
		else{

			$width = $width_orig;
			$height = $height_orig;
		}
		//if($new_width == 400)
			//die ('<div id="image">'.$width.' '.$height.' resize_to '.$resize_to.' resize_from '.$resize_from.' </div>');
		return array($width, $height);
	}

	private function compressSizeToResize($new_width, $new_height, $width_orig, $height_orig){

		$cross_double_1 = $width_orig;
		$cross_double_2 = $new_width;

		if($new_height > $new_width){

			$cross_double_1 = $height_orig;
			$cross_double_2 = $new_height;
		}

		$precent = $cross_double_2*100/$cross_double_1;

		$width = $width_orig*$precent/100;
		$height = $height_orig*$precent/100;

		return array($width, $height);
	}

	private function ImageCenterPosition($file, $width, $height){

		$new_w = $width;
		$new_h = $height;

		if(is_file($file)){

			list($old_w, $old_h) = getimagesize($file);
		}
		else{

			$old_w = imagesx($file);
			$old_h = imagesy($file);
		}

		if($old_w != $old_h && ($old_w < $new_w || $old_h < $new_h)){

			$min_w = $old_w-$old_h;
			$min_h = $old_h-$old_w;
			if($min_w < 0){

				$min = $old_w;
			}
			if($min_h < 0){

				$min = $old_h;
			}
			if($min <= 0){

				$min = $old_w;
			}
			if($new_w > $min){

				$new_w = $min;
			}
			if($new_h > $min){

				$new_h = $min;
			}
		}


		$vahe_w = round(($old_w-$new_w)/2, 0);
		$vahe_w = $vahe_w > 0 ? $vahe_w : 0;
		$vahe_h = round(($old_h-$new_h)/2, 0);
		$vahe_h = $vahe_h > 0 ? $vahe_h : 0;

		$old_x = $vahe_w;
		$old_y = $vahe_h;
		return array($old_w, $old_h, $old_x, $old_y);
	}

	//JPG
	private function CompressJPGImage ($file, $img_name, $width, $height, $location_dir, $watermark, $watermarkText='', $font_size, $font, $color, $resize){

		/*
		kompressime pildi kokku jpg
		*/
		$sizebeseem = true;

		$qua = 100; //kvaliteet


		list($width_orig, $height_orig) = getimagesize($file);
		if($resize){

			list($width, $height) = $this->compressSizeToResize($width, $height, $width_orig, $height_orig);
		}
		else{

			list($width, $height) = $this->compressSize($width, $height, $width_orig, $height_orig);
		}

		//echo ('<div id="background">'.$width.' '. $height.'</div>');


		$image_p = @imagecreatetruecolor($width, $height) or ($sizebeseem = false);
		if($sizebeseem == true){

			$image = imagecreatefromjpeg($file);
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
			if($watermark){

				if($watermarkText){

					$image_p = $this->WaterMarkText($image_p, $width, $height, $watermarkText, $font_size, $font, $color);
				}
				else{

					$image_p = $this->WaterMark($image_p, $watermark);
				}
			}
			@imagejpeg ($image_p,$location_dir.DIRECTORY_SEPARATOR.$img_name,$qua);
		}
		return $sizebeseem;
	}
	
	private function ResizeJPGImage ($file, $img_name, $width, $height, $location_dir){

		/*
		l�ikame pildi keskelt sobiva suuruse jpg
		*/

		$new_w = $width;
		$new_h = $height;
		$new_x = 0;
		$new_y = 0;
		$qua = 100;

		list($old_w, $old_h, $old_x, $old_y) = $this->ImageCenterPosition($file, $width, $height);

		$temp_img=imagecreatetruecolor($new_w, $new_h);
		$source_img = imagecreatefromjpeg($file);
		imagecopyresized($temp_img, $source_img, 0, 0, 0, 0, $new_w, $new_h, $old_w, $old_h);
		imagecopymerge($temp_img, $source_img, $new_x, $new_y, $old_x, $old_y, $old_w, $old_h, 100);
		imagejpeg ($temp_img, $location_dir.DIRECTORY_SEPARATOR.$img_name, $qua);
		imagedestroy($temp_img);
	}

	//GIF
	private function CompressGIFImage ($file, $img_name, $width, $height, $location_dir, $watermark, $watermarkText='', $font_size, $font, $color, $resize){

		/*
		kompressime pildi kokku gif
		*/

		$sizebeseem = true;

		$qua = 100; //kvaliteet

		list($width_orig, $height_orig) = getimagesize($file);
		if($resize){

			list($width, $height) = $this->compressSizeToResize($width, $height, $width_orig, $height_orig);
		}
		else{

			list($width, $height) = $this->compressSize($width, $height, $width_orig, $height_orig);
		}


		$image_p = @imagecreatetruecolor($width, $height) or ($sizebeseem = false);
		if($sizebeseem == true){

			$image = imagecreatefromgif($file);
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
			if($watermark){

				if($watermarkText){

					$image_p = $this->WaterMarkText($image_p, $width, $height, $watermarkText, $font_size, $font, $color);
				}
				else{

					$image_p = $this->WaterMark($image_p, $watermark);
				}
			}
			@imagegif ($image_p, $location_dir.DIRECTORY_SEPARATOR.$img_name);
		}
		return $sizebeseem;
	}
	
	private function ResizeGIFImage ($file, $img_name, $width, $height, $location_dir){

		/*
		l�ikame pildi keskelt sobiva suuruse gif
		*/

		$new_w = $width;
		$new_h = $height;
		$new_x = 0;
		$new_y = 0;
		$qua = 100;

		list($old_w, $old_h, $old_x, $old_y) = $this->ImageCenterPosition($file, $width, $height);

		$temp_img=imagecreatetruecolor($new_w, $new_h);
		$source_img = imagecreatefromgif($file);
		imagecopymerge($temp_img, $source_img, $new_x, $new_y, $old_x, $old_y, $old_w, $old_h, 100);
		@imagegif ($temp_img, $location_dir.DIRECTORY_SEPARATOR.$img_name);
		imagedestroy($temp_img);
	}


	//PNG
	private function CompressPNGImage ($file, $img_name, $width, $height, $location_dir, $watermark, $watermarkText='', $font_size, $font, $color, $resize){

		/*
		kompressime pildi kokku png
		*/

		$sizebeseem = true;

		$qua = 100; //kvaliteet

		list($width_orig, $height_orig) = getimagesize($file);
		if($resize){

			list($width, $height) = $this->compressSizeToResize($width, $height, $width_orig, $height_orig);
		}
		else{

			list($width, $height) = $this->compressSize($width, $height, $width_orig, $height_orig);
		}


		$image_p = @imagecreatetruecolor($width, $height) or ($sizebeseem = false);
		if($sizebeseem == true){

			$image = imagecreatefrompng($file);
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
			if($watermark){

				if($watermarkText){

					$image_p = $this->WaterMarkText($image_p, $width, $height, $watermarkText, $font_size, $font, $color);
				}
				else{

					$image_p = $this->WaterMark($image_p, $watermark);
				}
			}
			@imagepng ($image_p, $location_dir.DIRECTORY_SEPARATOR.$img_name);
		}
		imagedestroy($image_p);
		return $sizebeseem;
	}
	
	private function ResizePNGImage ($file, $img_name, $width, $height, $location_dir){

		/*
		l�ikame pildi keskelt sobiva suuruse gif
		*/

		$new_w = $width;
		$new_h = $height;
		$new_x = 0;
		$new_y = 0;
		$qua = 0;

		list($old_w, $old_h, $old_x, $old_y) = $this->ImageCenterPosition($file, $width, $height);

		$temp_img=imagecreatetruecolor($new_w, $new_h);
		$source_img = imagecreatefrompng($file);
		imagecopymerge($temp_img, $source_img, $new_x, $new_y, $old_x, $old_y, $old_w, $old_h, 100);
		@imagepng ($temp_img, $location_dir.DIRECTORY_SEPARATOR.$img_name);
		imagedestroy($temp_img);
	}

	private function WaterMark($source, $watermark){

		$watermarkImg = $watermark;

		$watermark = imagecreatefrompng($watermarkImg);

		$watermark_width = imagesx($watermark);
		$watermark_height = imagesy($watermark);

		$image = $source;
		switch($this->watermarkPos){

			case 'topleft':
				$pos_x = 5;
				$pos_y = 5;
			break;

			case 'topmiddle':
				$pos_x = (imagesx($source)-$watermark_width)/2;
				$pos_y = 5;
			break;

			case 'topright':
				$pos_x = (imagesx($source)-$watermark_width)-5;
				$pos_y = 5;
			break;

			case 'middleleft':
				$pos_x = 5;
				$pos_y = (imagesy($source)-$watermark_height)/2;
			break;

			case 'middleright':
				$pos_x = (imagesx($source)-$watermark_width)-5;
				$pos_y = (imagesy($source)-$watermark_height)/2;
			break;

			case 'bottomleft':
				$pos_x = 5;
				$pos_y = (imagesy($source)-$watermark_height)-5;
			break;

			case 'bottomcenter':
				$pos_x = (imagesx($source)-$watermark_width)/2;
				$pos_y = (imagesy($source)-$watermark_height)-5;
			break;

			case 'bottomright':
				$pos_x = (imagesx($source)-$watermark_width)-5;
				$pos_y = (imagesy($source)-$watermark_height)-5;
			break;

			default:
				$pos_x = (imagesx($source)-$watermark_width)/2;
				$pos_y = (imagesy($source)-$watermark_height)/2;
			break;
		}

		imagecopy($image, $watermark, $pos_x, $pos_y, 0, 0, $watermark_width, $watermark_height);
		imagedestroy($watermark);
		return $image;
	}

	private function WaterMarkText($source, $orig_w, $orig_h, $WaterMarkText, $font_size=10, $font_file='arial.ttf', $color) {

		$font_file = !$font_file ? 'arial.ttf' : $font_file;
		$font_size = !$font_size ? 10 : $font_size;

		$image_p = imagecreatetruecolor($orig_w, $orig_h);
		list($rgb1, $rgb2, $rgb3) = $color;
		$font_color = imagecolorallocate($image_p, $rgb1, $rgb2, $rgb3);
		$font = _DIR.'/templates/'._Template.'/fonts/'.$font_file;

		$this->imagettftext_cr($source, $font_size, 0, 0, 0, $font_color, $font, $WaterMarkText);
		return $source;
	}

	private function imagettftext_cr($im, $size, $angle, $x, $y, $color, $fontfile, $text){

		// retrieve boundingbox
		$bbox = imagettfbbox($size, $angle, $fontfile, $text);

		$x = $bbox[0] + (imagesx($im) / 2) - ($bbox[4] / 2) - 0;
		$y = $bbox[1] + (imagesy($im) / 2) - ($bbox[5] / 2) - 0;

		return imagettftext($im, $size, $angle, $x, $y, $color, $fontfile, $text);
	}

	private function ImageType($image){

		/*
		tuvastame image t��bi
		*/

		/*switch (exif_imagetype($image)){

			case 1:
				return 'gif';
			break;

			case 2:
				return 'jpg';
			break;

			case 3:
				return 'png';
			break;
		}*/
		if(preg_match('/\.gif/i', $image)) return 'gif';
		if(preg_match('/\.jpg/i', $image)) return 'jpg';
		if(preg_match('/\.jpeg/i', $image)) return 'jpg';
		if(preg_match('/\.png/i', $image)) return 'png';
	}
}
?>