<?
namespace Library;

class Register{

	use Component\Singleton;

	private $_object;

	public static function init(){

		Component\Register::register('REGISTER_OBJECT', new self(), Component\Register::IS_OBJECT);
		//pre(Component\Register::getRegister('REGISTER_OBJECT'));
		//echo seeend;
		//pre($GLOBALS);
	}

	private function setObject($name, $value){

		$this->_object[$name] = $value;
	}

	public static function set($name, $value){

		$self = new self();
		$self->setObject($name, $value);
		Component\Register::setRegister('REGISTER_OBJECT', $self);
	}

	public static function append(){
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