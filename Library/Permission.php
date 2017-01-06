<?
namespace Library;

class Permission {

	use \Library\Component\Singleton;

	public function permission( $role ){

		if( in_Array(\Library\Session::userData()->type, $role) )
			return true;
		return false;
	}

	public static function get( $route, $class, $method = 'Index' ){

		$loader = new \Library\Structur\Loader(false);
		$abstract = $loader->getAbstract( '\\'.$route.'\\'.$class.'\\Index' );
		list(, $privilege) = $loader->loadAbstract( $abstract );
		if( self::getPrivilege( $privilege, $class, $method ) )
			return true;
		return false;
	}

	public static function getPrivilege( $privilege, $class, $method ){

		if( is_Array($privilege[$method]) ){

			$privilegeArray = \Library\Session::userData() ? \Library\Session::userData(true)['privileges'][$class][$method] : array();
			if( empty($privilegeArray) )
				$privilegeArray = $privilege[$method];

			if( \Library\Session::userData()->level === 0 ){

				if( in_array(\Library\Session::userData()->type, $privilegeArray) )
					return true;
				return false;
			}

			if( in_array(\Library\Session::userData()->type, $privilegeArray) || in_array('*', $privilegeArray) )
				return true;
		}
		return false;
	}
}
?>