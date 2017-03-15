<?
namespace Library;

/**
* this class resizes images
*/
class Image{

	use Extension\Image;

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
	public static function resize($file, $width, $height, $location_dir){

		$self = new self();
		return $self->_resize($file, $width, $height, $location_dir);
	}

	public static function crop($file, $width, $height, $location_dir){

		$self = new self();
		return $self->_crop($file, $width, $height, $location_dir);
	}
}
?>