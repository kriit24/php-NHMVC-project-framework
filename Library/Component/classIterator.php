<?
namespace Library\Component;

class classIterator{

	const PRELOADERS = array('init');
	const METHOD_TYPES = array(
		'abstract' => 'isAbstract',
		'constructor' => 'isConstructor',
		'destructor' => 'isDestructor',
		'final' => 'isFinal',
		'private' => 'isPrivate',
		'protected' => 'isProtected',
		'public' => 'isPublic',
		'static' => 'isStatic'
	);

	public static function reflection($className){

		return new \ReflectionClass($className);
	}

	static function getMethods($className){

		$returnMethods = array();
		$reflection = new \ReflectionClass($className);
		$parentClass = $reflection->getParentClass();
		if( !preg_match('/_isPrivate/i', $parentClass->name) ){

			$methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
			foreach($methods as $method){

				if( !in_array($method->name, self::PRELOADERS) && substr($method->name, 0, 1) != '_' && ($method->class == $className || '\\'.$method->class == $className) ){

					$returnMethods[$method->name] = $method->class;
				}
			}
		}
		return $returnMethods;
	}

	static function getMethodType($className, $methodName, $type){

		$MethodChecker = new \ReflectionMethod($className, $methodName);
		$methodTypeToCheck = self::METHOD_TYPES[$type] ? self::METHOD_TYPES[$type] : 'public';
		if( $methodTypeToCheck )
			return $MethodChecker->$methodTypeToCheck();
	}

	function action( $methodName = null ){

		$method = preg_replace('/\W/s', '', ($methodName ? $methodName : $this->method));
		if( $method && (($this->requestRequired == true && $this->requestIsSet == true && $this->error == false) || $this->requestRequired == false) ){

			//$method = preg_replace('/\W/s', '_', $method);
			$arguments = func_get_args();

			if(method_exists($this, $method)){

				$ret = call_user_func_array(array($this, $method), ($arguments ? $arguments : array()));
				if( !isset($ret) )
					$ret = $this;
			}
			else{

				$reflection = $this->reflection($this);
				$namespace = $reflection->getNamespaceName();
				$className = '\\' . $namespace . '\\Action\\' . $method;
				$file = _DIR . DIRECTORY_SEPARATOR . _APPLICATION_PATH . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';

				if( is_file( $file ) ){

					$ret = call_user_func_array(array($className, self::PRELOADERS[0]), ($arguments ? $arguments : array()));
					if( !isset($ret) )
						$ret = $this;
				}
			}
		}

		unset($this->method);
		$this->requestRequired = false;
		return $ret;
	}
}

?>