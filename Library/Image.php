<?
namespace Library;

/**
* this class resizes images
*/
class Image{

	use Extension\Image, \Library\Component\Singleton;

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
	function imageResize($file, $img_name, $width, $height, $location_dir, $resize=true, $watermark=false, $watermarkText='', $font_size='', $font='', $color=array(0, 0, 0)){

		return $this->_imageResize($file, $img_name, $width, $height, $location_dir, $resize, $watermark, $watermarkText, $font_size, $font, $color);
	}
}
?>