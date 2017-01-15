<?
namespace Conf;

class Conf{

	/*
	const _DB_CONN = array(
		'_default' => array('_host' => '', '_port' => '', '_database' => '', '_user' => '', '_password' => '', '_driver' => 'mysql')
	);
	const _DB_REDIS = array('_host' => '', '_port' => '', '_password' => '');
	*/

	const _DB_CONN = false;
	const _DB_REDIS = false;
	const _DB_ROOT_DIR = 'Table';
	const _EMAIL = false;
	const _TEMPLATE = 'project';//also change in .htaccess file image location: RewriteRule ^Template/(.*)$ /application/Template/project/$1 [NC,L]
	const _LOG_REQUEST = false;
	const _DLANG = false;
	const _DEFAULT_ROUTE = 'Model';
	const _ROUTE_URL = 'simple';//advanced

	const LANGUAGE = array(
		'gb' => 'eng', 
		'ee' => 'est', 
		'ru' => 'rus'
	);


	//NB! DO NOT FORGET TO SET false in LIVE mode
	const _DEV_MODE = true;

	public function __construct(){

		//HIDDEN CONSTANTS:
		//_LANG - language
		//_DLANG - default language
		//_URI - base uri
		$this->error_reporting();

		date_default_timezone_set('Europe/Tallinn');

		define('_SHELL', (php_sapi_name() == 'cli' ? true : false));//for CRONJOB
		define('_DIR', get_include_path());
		define('_IP', $_SERVER['REMOTE_ADDR']);
		//autoload, template, view
		define('_DEBUG', (isset($_GET['debug']) && self::_DEV_MODE ? $_GET['debug'] : false));

		if( !is_dir(_DIR.'/tmp') ){

			mkdir(_DIR.'/tmp');
			file_put_contents(_DIR.'/tmp/.htaccess', "<Files *.*>\ndeny from all\nErrorDocument 403 /\n</Files>");
		}
	}

	public function error_reporting(){

		if( self::_DEV_MODE ){

			ini_set('display_errors', '1');
			error_reporting(E_ALL & ~E_NOTICE);
		}
		else{

			ini_set('display_errors', '0');
			error_reporting(0);
		}
	}
}

?>