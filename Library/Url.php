<?
namespace Library;

class Url{

	use Component\Singleton;

	private $routeName;

	public function toUrl($directoryPath = ''){

		return str_replace(get_include_path(), '', $directoryPath);
	}

	public function url($url = array(), $hideKey = array(), $mergeUrl = false){

		if( empty($url) )
			return '';

		if( !is_Array($url) ){

			$exp = explode('?', $_SERVER['REQUEST_URI']);
			parse_str(str_replace('?', '', $url), $url_1);
			parse_str(str_replace('?', '', $exp[1]), $url_2);
			$urlArray = array_merge($url_2, $url_1);
			unset($urlArray['_']);
			$urlString = $exp[0] . (!empty($urlArray) ? '?' : '') . http_build_query( $urlArray );
			return $urlString;
		}

		//pre($url);
		//pre($this->_parent . ' >>> ' . $this->_method);
		$url = array_merge($this->build( $url ), $url);
		//pre($url);

		$urlString = '';
		foreach($url as $key => $value){

			if( empty($value) && $value !== 0 )
				continue;

			$urlString .= $urlString ? '/' : '';
			$urlString .= !is_numeric($key) && !in_Array($key, array('route', $this->routeName, 'method')) ? $key.'/'.$value : $value;
		}

		if( substr($urlString, -1) != '/' && !preg_match('/\/\?/i', $urlString) )
			$urlString .= '/';

		return (preg_match('/\/admin\//i', $_SERVER['SCRIPT_NAME']) ? '/admin/' : '/').$urlString;
	}

	private function build($url){

		$retUrl = array();
		$this->routeName = strtolower( isset($url['route']) ? $url['route'] : \Conf\Conf::_DEFAULT_ROUTE );

		if( $url['route'] )
			$retUrl['route'] = $url['route'];

		if( !$url[$this->routeName] ){

			$exp = explode('/', (substr($this->_parent, 0, 1) != '/' ? '/' : '') . $this->_parent);
			$class = $exp[2];
			$retUrl[$this->routeName] = $class;
		}
		else
			$retUrl[$this->routeName] = $url[$this->routeName];

		if( !$url['method'] ){

			if( !in_array($this->_method, array('Index', 'Index_Admin', '__call')) ){

				//$exp = explode('/', (substr($this->_parent, 0, 1) != '/' ? '/' : '') . $this->_parent);
				//$method = $exp[2];
				$method = $this->_method;
				$retUrl['method'] = $method;
			}
		}
		else
			$retUrl['method'] = $url['method'];

		return $retUrl;
	}
}

?>