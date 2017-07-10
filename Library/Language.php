<?
namespace Library;

class Language{

	public static function init(){

		if( $_GET['language'] && strlen($_GET['language']) == 3 ) 
			$_SESSION['language'] = $_GET['language'];
		define('_LANG', ($_SESSION['language'] ? $_SESSION['language'] : \Conf\Conf::_DLANG));
		define('_DLANG', \Conf\Conf::_DLANG);
	}
}

?>