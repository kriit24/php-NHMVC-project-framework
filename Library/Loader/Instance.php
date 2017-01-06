<?
namespace Library\Loader;

class Instance extends \Library{

	protected static $_instances = array();

	public static function singleton( $class = '', $args = array() ) {

		if( !$class )
			$class = debug_backtrace(false, 2)[1]['class'];

        if (!isset(self::$_instances[$class])) {

			$reflection = new \ReflectionClass($class);
			if( !empty($args) )
	            self::$_instances[$class] = $reflection->newInstance($args);
			else
				self::$_instances[$class] = $reflection->newInstance();
        }
        return self::$_instances[$class];
    }

	private function getInstance( $className, $method, $args, $callParent ){

		$library = \Library\Component\Register::getRegister('LIBRARY');
		if( $library[$method] ){

			if( $callParent == 'callStatic' )
				return parent::__callStatic($method, $args);
			if( $callParent == 'call' )
				return parent::__call($method, $args);
		}
		
		$className .= '\\Controller\\' . $method;
		if( !method_Exists($className, 'init') && \Conf\Conf::_DEV_MODE == true )
			die('<b>Controller method dont exists: <span style="color:red;">' . $className . '</span> (class) <span style="color:red;">' . $method . '</span> (method)</b>');
		return call_user_func_array(array('\\'.$className, 'init'), $args);
	}

	private static function className( $className ){

		$exp = explode('\\', $className);
		unset( $exp[ count($exp)-1 ] );
		return implode('\\', $exp);
	}

	public function __call($method, $args){

		$className = self::className( debug_backtrace(false, 2)[1]['class'] );
		return $this->getInstance( $className, $method, $args, 'call' );
	}

	public static function __callStatic($method, $args){

		$className = self::className( debug_backtrace(false, 2)[1]['class'] );
		return $this->getInstance( $className, $method, $args, 'callStatic' );
	}
}
?>