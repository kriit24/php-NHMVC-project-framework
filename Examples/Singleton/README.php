<?

class example{

	use \Library\Component\Singleton;
}

example::singleton();

class example{

	use \Library\Component\Singleton;

	public function __construct($param1, $param2){

		echo $param;
	}
}

example::singleton('value', 'value2');

?>