<?
namespace Library\Component;

class Register{

	const IS_ARRAY = 'array';
	const IS_OBJECT = 'object';
	const IS_STRING = 'string';
	const IS_INTEGER = 'integer';
	const IS_BOOLEAN = 'boolean';
	private $_Register = null;

	private function setRegisterValue( $value ){

		$this->_Register = $value;
	}

	static function register($name, $value, $type){

		if( gettype($value) == $type ){

			$self = new self;
			$self->setRegisterValue( $value );

			$GLOBALS[$name] = $self;
			$GLOBALS['REGISTER_TYPE'][$name] = $type;
		}
	}

	static function inRegister($name){

		if( isset($GLOBALS[$name]) )
			return true;
		return false;
	}

	static function setRegister($name, $value, $merge = true){

		if( isset($GLOBALS[$name]) && self::checkValueByType($name, $value) ){

			$mergeValue = self::getRegister($name);
			$type = $GLOBALS['REGISTER_TYPE'][$name];
			$setValue = null;

			if( $type == self::IS_ARRAY && $merge ){

				$setValue = array_merge($mergeValue, $value);
			}
			else if( $type == self::IS_ARRAY && !$merge ){

				$setValue = $value;
			}
			else if($type == self::IS_OBJECT){

				$setValue = $value;
			}
			else if($type == self::IS_STRING && $merge){

				$setValue = $mergeValue . $value;
			}
			else if($type == self::IS_STRING && !$merge){

				$setValue = $value;
			}


			/*echo $name.'<br>';
			pre($setValue);*/
			if( isset($setValue) ){

				$self = new self;
				$self->setRegisterValue( $setValue );
				$GLOBALS[$name] = $self;
			}
			else{

				die('Cannot append');
			}
		}
	}

	private static function checkValueByType($name, $value){

		$type = $GLOBALS['REGISTER_TYPE'][trim($name)];
		if( gettype($value) == $type )
			return true;
		return false;
	}

	static function getRegister($name){

		if( self::checkValueByType($name, $GLOBALS[$name]->_Register) )
			return $GLOBALS[$name]->_Register;
		return false;
	}

	static function deleteRegister($name){

		unset( $GLOBALS[$name] );
	}

	static function clearRegister(){

		unset($GLOBALS);
	}

	static function end($autoloader){

		if( $autoloader )
			self::clearRegister();
	}
}

?>