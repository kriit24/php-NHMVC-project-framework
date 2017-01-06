<?
namespace Library\Component;

trait Singleton{

	//WORKING version
	/*public static function singleton( $class = '', $args = array() ) {

		if( !$class )
			$class = debug_backtrace(false, 2)[1]['class'];

		echo 'SINGLETON='.$class.'<br>';

        if (!isset(self::$_instances[$class])) {

			$reflection = new \ReflectionClass($class);
			if( !empty($args) )
	            self::$_instances[$class] = $reflection->newInstanceArgs($args);
			else
				self::$_instances[$class] = $reflection->newInstance();
        }
        return self::$_instances[$class];
    }*/

	//TESTING version
	public static function singleton( $class = '', $args = array() ){

		if( !$class )
			$class = get_called_class();
		/*echo $class.'<br>';
		if( $class === __CLASS__ )
			$class = debug_backtrace(false, 2)[1]['class'];
		*/
		if( !$args ){

			$args = func_get_args();
			if( $args[0][0] && is_array($args[0]) )
				$args = $args[0][0];
		}

		$reflection = new \ReflectionClass($class);
		if( !empty($args) && $reflection->getConstructor() )
            $_instances = $reflection->newInstanceArgs($args);
		else
			$_instances = $reflection->newInstance();
        return $_instances;
	}
}
?>