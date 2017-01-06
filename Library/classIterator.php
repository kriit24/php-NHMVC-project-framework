<?
namespace Library;

class classIterator extends Component\classIterator{

	use \Library\Component\Singleton;

	private $_option = array();

	function getPropertyValue($propertyObject, $propertyName){

		$reflection = parent::reflection(get_class($propertyObject));
		$property = $reflection->getProperty($propertyName);
		$property->setAccessible(true);
		return $property->getValue($propertyObject);
	}

	public static function getSiblingClass(){

		return debug_backtrace(false, 2)[1]['class'];
	}

	public function setOption( $name, $value ){

		$this->_option[$name] = $value;
	}

	public function toOption( $name, $value ){

		if( gettype($value) == 'array' )
			$this->_option[$name] = array_merge($this->_option[$name], $value);
		else if( gettype($value) == 'object' )
			$this->_option[$name] = (object)array_merge((array)$this->_option[$name], (array)$value);
		else
			$this->_option[$name] .= $value;
	}

	public function getOption( $name ){

		return $this->_option[$name];
	}
}

?>