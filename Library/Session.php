<?
namespace Library;

class Session{

	const SESSION_TIME = 60*30;//60 seconds (1 minute) * 30 minute
	const SESSION_NAME = 'PHPSESSID';

	private $_Session = null;

	public static function init(){

		session_name( self::SESSION_NAME );
		session_start();

		if( !isset($_SESSION['initiated']) ){

			session_regenerate_id();
			$_SESSION['initiated'] = true;
		}

		self::set();
		session_write_close();
	}

	public static function sessionExpires(){

		return time()+(\Library\Session::SESSION_TIME);
	}

	public static function reGenId(){

		session_start();
		session_regenerate_id();
		session_write_close();
	}

	public static function set( $value = null, $path = '/' ){

		if( !$value )
			$value = session_id();

		setcookie( self::SESSION_NAME, $value, self::sessionExpires(), '/');
	}

	public static function clear($name = ''){

		self::sessionStart();

		if( $name )
			unset($_SESSION[$name]);
		else{

			foreach($_SESSION as $key => $value)
				unset($_SESSION[$key]);
			self::set( 'false' );
		}

		session_write_close();
	}

	private static function sessionStart(){

		ini_set('session.use_only_cookies', false);
		ini_set('session.use_cookies', false);
		//ini_set('session.use_trans_sid', false);
		ini_set('session.cache_limiter', null);
		session_start(); // second session_start
	}

	private function setSessionValue( $value ){

		$this->_Session = $value;
	}

	/*
	\Library\Session::singleton()->arraySessionName();//return array as object
	\Library\Session::singleton()->arraySessionName(true);//return array
	\Library\Session::singleton()->arraySessionName(array());//registers or modifies session
	\Library\Session::singleton()->arraySessionName('name', 'value');//registers or modifies session 
	*/

	public static function __callStatic($name, $args){

		$sessionWrite = false;

		$value = isset($args[0]) ? $args[0] : null;
		$overWrite = isset($args[1]) ? $args[1] : false;
		$sessionValue = null;

		if( $name && !$_SESSION[$name] ){

			$sessionWrite = true;
			self::sessionStart();

			$sessionValue = $value;
		}
		else if( $name && $_SESSION[$name] ){

			$sessionWrite = true;
			self::sessionStart();

			if( gettype($value) == 'array' && isset($value) ){

				$sessionValue = is_array($_SESSION[$name]->_Session) ? array_replace($_SESSION[$name]->_Session, $value) : $value;
			}
			else if(gettype($value) == 'boolean' && gettype($_SESSION[$name]->_Session) == 'boolean' && isset($value)){

				$sessionValue = $value;
			}
			else{

				if( gettype($value) != 'boolean' && isset($value) )
					$sessionValue = $value;
			}

			if( $overWrite )
				$sessionValue = $value;
		}

		if( isset($sessionValue) ){

			$self = new self;
			$self->setSessionValue( $sessionValue );

			$_SESSION[$name] = $self;
		}

		if( $sessionWrite ){

			session_write_close();
		}

		if( gettype($value) == 'boolean' && $value == true )
			return $_SESSION[$name]->_Session;

		return json_decode(json_encode($_SESSION[$name]->_Session), false);
	}
}

?>