<?
namespace Library;

class Session{

	private $_Session = null;

	private function setSessionValue( $value ){

		$this->_Session = $value;
	}

	static function clear($name = ''){

		self::sessionStart();

		if( $name )
			unset($_SESSION[$name]);
		else
			unset($_SESSION);

		session_write_close();
	}

	private static function sessionStart(){

		ini_set('session.use_only_cookies', false);
		ini_set('session.use_cookies', false);
		//ini_set('session.use_trans_sid', false);
		ini_set('session.cache_limiter', null);
		session_start(); // second session_start
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