<?
namespace Command;

class Command extends \Library\Structur\Loader{

	public function __construct(){

		parent::__construct();
		if( _SHELL && $_GET['route'] == 'Command' && !$this->isMethodAccessible[$this->getClass()][$this->getMethod()] )
			die("Not accessible\ncommand:".$this->getClass()."\n method:".$this->getMethod()."\n");
	}

	public static function install(){

		if( is_Array(\Conf\Conf::_DB_CONN) ){

			if( \Session::userData()->level === 0 ){

				\Session::clear('userData');
				\Session::clear('initiated');
				die(header('Location://'.$_SERVER['HTTP_HOST']));
			}

			return true;
		}

		if( $_GET['route'] || (preg_match('/\/admin/i', $_SERVER['SCRIPT_URI']) && !is_Array(\Conf\Conf::_DB_CONN)) ){

			\Session::clear('userData');
			\Session::clear('initiated');
			die(header('Location://'.$_SERVER['HTTP_HOST']));
		}

		if( \Session::userData()->level !== 0 ){

			\Session::clear('userData');
			\Session::clear('initiated');
			die(header('Location://'.$_SERVER['HTTP_HOST']));
		}
		return false;
	}
}

?>