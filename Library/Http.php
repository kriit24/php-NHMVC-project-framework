<?
namespace Library;

/*
to get real $_POST AND $_GET request
*/

class Http extends Component\isPrivate{

	public static function Post(){

		return Component\Register::getRegister('HTTP_POST');
	}

	public static function Get(){

		return Component\Register::getRegister('HTTP_GET');
	}

	/**
	* it will redirect to current url
	* 
	* @param String $url
	*/
	public static function redirect( $url ){

		self::reload( $url, false );
	}

	public static function goBack(){

		die( '<script type="text/javascript">window.history.back();</script>' );
	}

	public static function reload( $url = '', $append = true ){

		die( '<script type="text/javascript">window.location.href = ' . ($append ? 'window.location.href' : '') . ($append && $url ? '+' : '') . ($url ? '"'.$url.'"' : '') . ';</script>' );
	}

	/**
	* same as redirect, but uses header('Location:$url')
	* 
	* @param String $url
	*/
	public static function location( $url ){

		die(header('Location: '.$url));
	}
}
?>