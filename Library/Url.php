<?
namespace Library;

class Url extends classIterator{

	use Component\Singleton;

	public function toUrl($directoryPath = ''){

		return str_replace(_DIR, '', $directoryPath);
	}

	public function url($url = array(), $hideKey = array(), $mergeUrl = false){

		if( empty($url) )
			return '';

		if( !is_Array($url) )
			return $this->regularUrl($url);

		$url = $this->mergeUrl($url);

		$this->setOption('url', '');

		//SET CORRECT QUEUE
		$tmp = array();
		//first must be route
		$routeName = $url['route'] ? $url['route'] : \Conf\Conf::_DEFAULT_ROUTE;
		if( $url['route'] ){

			$tmp[] = $url['route'];
			unset($url['route']);
		}
		//second must be route name
		if( $url[strtolower($routeName)] ){

			$tmp[] = $url[strtolower($routeName)];
			unset($url[strtolower($routeName)]);
		}
		//third must be method
		if( $url['method'] ){

			$tmp[] = $url['method'];
			unset($url['method']);
		}
		$url = array_merge($tmp, $url);

		if( \Conf\Conf::_ROUTE_URL == 'advanced' ){

			$this->advancedUrl( $url );
		}
		else{

			$this->basicUrl( $url );
		}

		$urlString = $this->getOption('url');
		if( substr($urlString, -1) != '/' && !preg_match('/\/\?/i', $urlString) )
			$this->toOption('url', '/');

		return (preg_match('/\/admin\//i', $_SERVER['SCRIPT_NAME']) ? '/admin/' : '/').$this->getOption('url');
	}

	private function regularUrl($url){

		$requestUri = substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], '/')+1);
		$uri = substr($_SERVER['REQUEST_URI'], strrpos($_SERVER['REQUEST_URI'], '/')+1);
		$uri = substr($uri, 1);
		$uriArray = array();
		$matches = array();

		preg_match_all('/([a-zA-Z0-9\_\-\+_]+)\=([a-zA-Z0-9\_\-\+_]+)/s', $uri, $matches);
		foreach($matches[1] as $k => $v)
			$uriArray[$v] = $matches[2][$k];

		if( in_array(substr($url, 0, 1), array('?', '&')) )
			$url = substr($url, 1);

		$matches = array();
		preg_match_all('/([a-zA-Z0-9\_\-\+_]+)\=([a-zA-Z0-9\_\-\+_]+)/s', $url, $matches);
		foreach($matches[1] as $k => $v)
			unset($uriArray[$v]);

		unset($uri);
		foreach($uriArray as $k => $v)
			$uri .= $uri ? '&' . $k . '=' . $v : $k . '=' . $v;

		return $requestUri .'?'. $uri . ($uri && $url ? '&' : '') . $url;
	}

	private function advancedUrl( $urlArray ){

		$this->setOption('canonical_url', '');

		foreach($tmp as $key => $value){

			if( empty($value) && $value !== 0 )
				continue;

			$this->toOption('canonical_url', ($canonical_url ? '&' : '?').$key.'='.$value);
			if( !in_array($key, $hideKey) )
				$this->toOption('url', '/'.$value);
		}

		$this->writeUrl($this->getOption('url'), $this->getOption('canonical_url'));
	}

	private function basicUrl( $urlArray ){

		$url = '';
		//pre($urlArray);
		foreach($urlArray as $key => $value){

			if( empty($value) && $value !== 0 )
				continue;

			$url .= $url ? '/' : '';
			$url .= !is_numeric($key) ? $key.'/'.$value : $value;
		}
		$this->toOption('url', $url);
	}

private function mergeUrl($url){

		$caller = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 4);
		$ret = $url;
		//pre($caller);

		$_parent = dirname(str_replace(array(_DIR, _APPLICATION_PATH, '//'), '', $caller[3]['file']));
		$_route = array_shift(explode('/', $_parent));
		$_method = $caller[3]['function'];

		if( $_route ){

			if( !$url['route'] && !$url[strtolower($_route)] && !$_GET['route'] && !$_GET[strtolower($_route)] ){

				$ret['route'] = $_route;
			}
			if( !$url['route'] && !$url[strtolower($_route)] && $_route ){

				$ret[strtolower($_route)] = explode('/', $_parent)[1];
			}
		}
		if( !$url['method'] && !in_array($_method, array('Index', 'Index_Admin', '__call')) ){

			$_method = $this->findClassMethod( $caller, 0 );
			if( $_method )
				$ret['method'] = $_method;
		}
		return $ret;
	}

	private function findClassMethod( $caller, $subtracter ){

		$callerFile = null;

		for($i = 3; $i >= 0; $i--){

			if( !$callerFile && preg_match('/' . _APPLICATION_PATH . '/i', $caller[$i]['file']) ){

				$callerFile = $caller[$i]['file'];
				$_method = $caller[ ($i - $subtracter) ]['function'];

				$className = $this->getClassName( $callerFile );
				$reflection = $this->reflection( $className );
				if( $reflection->hasMethod( $_method ) ){

					$methodClassArray = $reflection->getMethod( $_method );
					$methodClass = $methodClassArray->{'class'};
					if( $methodClass[0] != '\\' )
						$methodClass = '\\' . $methodClass;

					//IF CONDITION CAN MAKE PROBLEMS
					if( $methodClass == $className ){

						return $_method;
					}
				}
			}
		}
		return;
	}

	private function getClassName( $callerFile ){

		$directoriesAndFilename = explode('/', explode(_APPLICATION_PATH, $callerFile)[1]);
		$nameAndExtension = explode('.', implode('\\', $directoriesAndFilename));
		return array_shift($nameAndExtension);
	}

	private function writeUrl($logical_uri, $canonical_url){

		$route = Table('route');

		$route->Select()
			->column('SQL_CACHE *')
			->from("route USE INDEX(logical_url)")
			->where("logical_url = '".addslashes($logical_uri)."'")
			->fetch();
		if( $route->Numrows() == 0 ){

			$route->Insert(array(
				'canonical_url' => addslashes($canonical_url),
				'logical_url' => addslashes($logical_uri),
				'created_at' => 'NOW()'
				));
		}
		else{

			$row = $route->Assoc();
			if( stripslashes($row['canonical_url']) != $canonical_url ){

				$route->Update(
					array('canonical_url' => addslashes($canonical_url)),
					array('id' => $row['id'])
				);
			}
		}
	}
}

?>