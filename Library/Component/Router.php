<?
namespace Library\Component;

class Router extends \Library\classIterator{

	const IS_FILE = 'is_file';
	const IS_DIR = 'is_dir';

	static function init(){

		if( $_SERVER['HTTP_HOST'] ){

			$_URI = str_replace('/admin', '', $_SERVER['SCRIPT_URI']);
			if( substr($_URI, -1) == DIRECTORY_SEPARATOR )
				$_URI = substr($_URI, 0, -1);
			if( $_SERVER['REDIRECT_URL'] )
				$_URI = '//'.$_SERVER['HTTP_HOST'];
		}
		else
			$_URI = '//test.projectpartner.ee';//DEFAULT_URI
		define('_URI', strip_tags($_URI));

		if( _SHELL )
			$_GET['route'] = trim($_SERVER['argv'][1]);

		Register::setRegister('HTTP_GET', self::routeInjection($_GET, false));
		Register::setRegister('HTTP_POST', self::routeInjection($_POST, false));

		$_GET = self::htmlInjection( self::routeInjection($_GET, true) );
		$_POST = self::htmlInjection( self::routeInjection($_POST, true) );

		self::checkRoute();
	}

	private static function routeInjection( $array, $escape = true ): array{

		$ret = array();

		foreach($array as $k => $v){

			if( is_array($v) )
				$ret[$k] = self::routeInjection($v, $escape);
			else{

				if( !preg_match('/show tables/i', strtolower($v)) && !preg_match('/show columns/i', strtolower($v)) && !preg_match('/select(.*?)from/i', strtolower($v)) ){

					if( $escape )
						$ret[$k] = self::escape($v);
					else
						$ret[$k] = $v;
				}
			}
		}
		return $ret;
	}

	private static function htmlInjection( $array ): array{

		$ret = array();

		foreach($array as $k => $v){

			if( is_array($v) )
				$ret[$k] = self::htmlInjection($v);
			else{

				$ret[$k] = self::striptags($v);
			}
		}
		return $ret;
	}

	private static function buildRoute($url){

		$exp = explode('/', $url);
		$merge = array();

		//paired
		if( count(explode('/', $url)) %2 ){

			//odd
			if( !is_dir(_DIR .DIRECTORY_SEPARATOR. _APPLICATION_PATH .DIRECTORY_SEPARATOR. $exp[0]) ){

				$routeName = \Conf\Conf::_DEFAULT_ROUTE;
				$className = $exp[0];
				unset($exp[0]);
			}
			else{

				$routeName = $exp[0];
				unset($exp[0]);
			}

			//if there is only one value remaining
			if( count($exp) == 1 ){

				$className = $exp[0];
				unset($exp[0]);
			}
			
			//if there is multi value remaining
			if( !empty($exp) ){

				if( count($exp) %2 ){

					//odd
					$className = $exp[1];
					unset($exp[1]);
				}
				else{

					//paired
					$classFile = _DIR .DIRECTORY_SEPARATOR. _APPLICATION_PATH .DIRECTORY_SEPARATOR. $routeName .DIRECTORY_SEPARATOR. $exp[1];
					if( is_dir($classFile) ){

						$className = $exp[1];
						$methodName = $exp[2];
						unset($exp[1]);
						unset($exp[2]);
					}
				}
			}
		}
		else{

			//paired
			if( !is_dir(_DIR .DIRECTORY_SEPARATOR. _APPLICATION_PATH .DIRECTORY_SEPARATOR. $exp[0]) ){

				$routeName = \Conf\Conf::_DEFAULT_ROUTE;
				$className = $exp[0];
				$methodName = $exp[1];
			}
			else{

				$routeName = $exp[0];
				$className = $exp[1];
				$methodName = '';
				if( $exp[2] ){

					$classFile = _DIR .DIRECTORY_SEPARATOR. _APPLICATION_PATH .DIRECTORY_SEPARATOR. $routeName .DIRECTORY_SEPARATOR. $className;
					if( is_dir($classFile) ){

						if( method_exists('\\' . $routeName . '\\' . $className . '\\Index', $exp[2]) ){

							$methodName = $exp[2];
							unset($exp[2]);
						}
					}
				}
			}
			unset($exp[0]);
			unset($exp[1]);
		}
		if( is_array($exp) ){

			$next = '';
			foreach($exp as $k => $v){

				if( !preg_match('/\=/i', $v) ){

					if( $v && $next != $v ){

						$merge[$v] = $exp[$k+1];
						$next = $exp[$k+1];
					}
				}
				else{

					list($key, $value) = explode('=', str_replace('?', '', $v));
					$merge[$key] = $value;
				}
			}
		}
		return array_merge(array('route' => $routeName, strtolower($routeName) => $className, 'method' => $methodName), $merge);
	}

	private static function getRoute($name){

		$route = new \Table\route;
		$row = $route->Select()
		->column('SQL_CACHE *')
		->from("route USE INDEX(logical_url)")
		->where("logical_url = '".addslashes($name)."' ")
		->fetch();
		return $row;
	}

	private static function checkRoute(){

		if( $_GET['route'] ){

			$request = (substr($_GET['route'], 0, 1) == DIRECTORY_SEPARATOR ? substr($_GET['route'], 1) : $_GET['route']);
			$request = (substr($request, -1) == DIRECTORY_SEPARATOR ? substr($request, 0, -1) : $request);
			unset($_GET['route']);
			$request = self::striptags(self::escape($request));
			$request = str_replace('%C3%B5', 'õ', $request);
			$request = str_replace('%C3%A4', 'ä', $request);
			$request = str_replace('%C3%B6', 'ö', $request);
			$request = str_replace('%C3%BC', 'ü', $request);
			$request = str_replace('%C3%95', 'Õ', $request);
			$request = str_replace('%C3%84', 'Ä', $request);
			$request = str_replace('%C3%96', 'Ö', $request);
			$request = str_replace('%C3%9C', 'Ü', $request);
			if(preg_match('/\+/i', $request)){

				$requestTmp = $request;
				$request = '';

				foreach(explode('+', $requestTmp) as $v){

					$request .= $request ? '+'.urldecode($v) : urldecode($v);
				}
			}
			else{

				$request = urldecode($request);
			}
			if( \Conf\Conf::_ROUTE_URL == 'simple' ){

				$_GET = array_merge($_GET, self::buildRoute($request));
			}
			if( \Conf\Conf::_ROUTE_URL == 'advanced' ){

				if( substr($request, 0, 1) != DIRECTORY_SEPARATOR )
					$request = DIRECTORY_SEPARATOR.$request;
				if( substr($request, -1) != DIRECTORY_SEPARATOR )
					$request = $request.DIRECTORY_SEPARATOR;

				$row = self::getRoute($request);
				if($row['canonical_url']){

					$canonical_url = str_replace(array('/?', '?'), '', $row['canonical_url']);
					foreach(explode('&', $canonical_url) as $row){

						list($key, $value) = explode('=', $row);
						$_GET[$key] = $value;
					}
				}
			}
		}

		if( \Conf\Conf::_LOG_REQUEST == true )
			self::logUri();
	}

	private static function logUri(){

		foreach(array('user', 'user_name', 'username', 'pw', 'password') as $v){

			unset($POST[$v]);
			unset($GET[$v]);
		}
		$log = Table('log');

		$log->Insert(array(
			'action' => 'REQUEST',
			'data' => addslashes(serialize(array('GET' => $GET, 'POST' => $POST))),
			'created_by' => (\Library\Session::userData()->name ? \Library\Session::userData()->name : 'anonymous'),
			'created_by_id' => (\Library\Session::userData()->user_id ? \Library\Session::userData()->user_id : 0),
			'created_at' => 'NOW()',
			), true);
	}

	private static function escape($str){

		$search=array("\\","\0","\n","\r","\x1a","'",'"');
		$replace=array("\\\\","\\0","\\n","\\r","\Z","\'",'\"');
		return str_replace($search,$replace,html_entity_decode($str));
	}

	private static function reEscape($str){

		$replace=array("\\","\0","\n","\r","\x1a","'",'"');
		$search=array("\\\\","\\0","\\n","\\r","\Z","\'",'\"');
		return str_replace($search,$replace,html_entity_decode($str));
	}

	private static function striptags($str){

		return strip_tags($str);
	}
}

?>