<?
namespace Library;

class Validate extends Component\isPrivate{

	use Extension\Validate;

	const MESSAGE = array(
		'is_set' => 'Please fill {key}',
		'is_match' => '{key} not match',
		'is_string' => '{key} must contain only a-zA-Z values',
		'is_numeric' => '{key} must contain only 0-9 values',
		'is_email' => '{key} must be in correct email format',
		'is_equal' => '{key} and {key1} must be equal',
		'is_password' => '{key} requirements are: {key1} letters, a-zA-Z AND 0-9',
		'in_array' => '{key} not in array',
		'validator' => '{key} not valid',
	);
	private $validatorMessage;
	private $validMessage;

	public function message( $message = '' ){

		if( !$message )
			$message = self::MESSAGE[ $this->methodName ];

		$array = array('key' => str_replace('_', ' ', ucfirst($this->name_1)), 'key1' => str_replace('_', ' ', $this->name_2));
		$message = \Library\Replace::singleton()->replace($message, $array);

		$this->validMessage[$this->name][$this->methodName] = $message;

		if( !isset($this->validatorMessage[$this->name]) )
			$this->validatorMessage[$this->name] = $message;
	}

	public static function get( $name ){

		$validators = \Library\Component\throwException::catch('VALIDATE_MESSAGE');
		return $validators[$name];
	}

	public function isValid($onAction, $data){

		new \Library\Component\throwException(array($onAction => $this->validatorMessage), 'VALIDATE_MESSAGE');

		if( !$data )
			return false;

		if( !$data[$onAction] )
			return false;

		return $this->_isValid($data);
	}

	/**
	* automated call of element
	* @example $this->elementName(array('type=checkbox'))
	*/
	public function __call($method, $args){

		$methodName = strtolower('_'.$method);
		if( !method_Exists($this, $methodName) )
			die('Method: <b>' . $methodName . '</b> does not exists');
		$this->validateData[] = array('method' => $methodName, 'args' => $args);
		$this->methodName = strtolower($method);
		$this->name = $args[0];
		$this->name_1 = $args[0];
		$this->name_2 = $args[1];
		$this->message();
		return $this;
	}
}

?>