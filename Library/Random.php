<?
namespace Library;

class Random{

	use \Library\Component\Singleton;

	function rand($length){

		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	    return substr(str_shuffle($chars), 0, $length);
	}
}

?>