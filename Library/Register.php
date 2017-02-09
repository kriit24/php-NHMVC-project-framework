<?
namespace Library;

class Register{

	use Component\Singleton;

	private $_object;

	public static function init(){

		Component\Register::register('REGISTER_OBJECT', new self(), Component\Register::IS_OBJECT);
	}

	private function setObject($name, $value){

		$this->_object[$name] = $value;

		if( Component\Register::inRegister('REGISTER_OBJECT') ){

			$object = Component\Register::getRegister('REGISTER_OBJECT');
			if( $object->_object[$name] )
				$this->_object[$name] = array_replace($object->_object[$name], $this->_object[$name]);
		}
	}

	public static function set($name, $value){

		$self = new self();
		$self->setObject($name, $value);
		Component\Register::setRegister('REGISTER_OBJECT', $self);
	}

	public static function get($name = null){

		if( $name ){

			$register = Component\Register::getRegister('REGISTER_OBJECT');
			return $register->_object[$name];
		}
		else
			return Component\Register::getRegister('REGISTER_OBJECT');
	}
}
?>