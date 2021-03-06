<?
namespace Library\Loader;

class Library extends \Library\Component\Extension{

	use \Library\Component\Singleton;

	static function init(){

		if( $cache = \Library\Component\Cache::get('LIBRARY') ){

			\Library\Component\Register::register('LIBRARY', $cache['classMethods'], \Library\Component\Register::IS_ARRAY);
			\Library\Component\Register::register('AUTOLOAD_END', $cache['autoloadEnd'], \Library\Component\Register::IS_ARRAY);
			return true;
		}

		$path = dirname(__DIR__);
		$classMethods = array();
		$autoloadEnd = array('Library\Component\Register');

		foreach( glob($path .DIRECTORY_SEPARATOR. '*.*') as $file ){

			if( !in_Array(basename($file), array('Autoload.php', '_Autoload.php')) ){

				require_once $file;
				$className = '\\Library\\'.str_replace('.php', '', basename($file));
				$methods = \Library\classIterator::getMethods( $className );
				if( is_array($methods) ){

					if( $methods['end'] && \Library\classIterator::getMethodType( $className, 'end', 'static' ))
						$autoloadEnd[] = $methods['end'];
					$classMethods = array_merge($classMethods, $methods);
				}
			}
		}
		\Library\Component\Cache::set('LIBRARY', array('classMethods' => $classMethods, 'autoloadEnd' => $autoloadEnd));
		\Library\Component\Register::register('LIBRARY', $classMethods, \Library\Component\Register::IS_ARRAY);
		\Library\Component\Register::register('AUTOLOAD_END', $autoloadEnd, \Library\Component\Register::IS_ARRAY);
	}

	/**
	* its alias of
	* print '<pre>';
	* print_r($array);
	* print '</pre>';
	* 
	* @param Array $array or Object
	*/
	function __call( $method, $args ){

		$library = \Library\Component\Register::getRegister('LIBRARY');
		if( $library[$method] ){

			$className = $library[$method];
			$caller = debug_backtrace(false, 4);
			
			if( preg_match('/'._APPLICATION_PATH.'/i', $caller[0]['file']) ){

				$exp = explode(_APPLICATION_PATH, $caller[0]['file']);
				$_parent = substr($exp[1], 0, strrpos($exp[1], '/'));
				$_method = $caller[0]['function'];
			}
			else if( preg_match('/'._APPLICATION_PATH.'/i', $caller[1]['file']) ){

				$exp = explode(_APPLICATION_PATH, $caller[1]['file']);
				$_parent = substr($exp[1], 0, strrpos($exp[1], '/'));
				$_method = $caller[1]['function'];
			}
			else if( preg_match('/'._APPLICATION_PATH.'/i', $caller[2]['file']) ){

				$exp = explode(_APPLICATION_PATH, $caller[2]['file']);
				$_parent = substr($exp[1], 0, strrpos($exp[1], '/'));
				$_method = $caller[2]['function'];
			}
			else if( preg_match('/'._APPLICATION_PATH.'/i', $caller[3]['file']) ){

				$exp = explode(_APPLICATION_PATH, $caller[3]['file']);
				$_parent = substr($exp[1], 0, strrpos($exp[1], '/'));
				$_method = $caller[3]['function'];
			}
			else{

				$exp = explode(_APPLICATION_PATH, $caller[1]['file']);
				if( $exp[1] )
					$_parent = substr($exp[1], 0, strrpos($exp[1], '/'));
				else
					$_parent = $caller[1]['class'];
				$_method = $caller[1]['function'];
			}

			$class = new $className(true);
			$class->_parent = $_parent;
			$class->_method = $_method;
			return call_user_func_array(array($class, $method), $args);
		}
		if( method_exists($this, $method) ){

			list($route, $className, ) = explode('\\', get_class($this));
			if( $permission = \Library\Permission::get($route, $className, $method) ){

				return call_user_func_array(array($this, $method), $args);
			}
			return new \stdClass();
		}
		die('<b style="color:red;">Method not found:</b> '.$method.'<br><br><pre>'.\Library\Component\Trace::get().'</pre>');
	}

	public static function __callStatic( $method, $args ){
		
		$library = \Library\Component\Register::getRegister('LIBRARY');
		if( $library[$method] ){

			$className = $library[$method];
			$caller = debug_backtrace(false, 4);
			$class = new $className(true);
			$class->_parent = $caller[3]['class'];
			$class->_method = $caller[3]['function'];
			return call_user_func_array(array($class, $method), $args);
		}
		if( method_exists(get_called_class(), $method) ){

			list($route, $className, ) = explode('\\', get_called_class());
			if( $permission = \Library\Permission::get($route, $className, $method) ){

				return call_user_func_array(array(get_called_class(), $method), $args);
			}
			return new \stdClass();
		}
		die('<b style="color:red;">Method not found:</b> '.$method.'<br><br><pre>'.\Library\Component\Trace::get().'</pre>');
	}
}

?>