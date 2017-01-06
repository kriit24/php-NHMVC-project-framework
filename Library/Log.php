<?
namespace Library;

class Log extends Component\Log{

	public function __construct($parentName = '', $name = '', $historyLength = 0){

		parent::__construct($parentName, $name, $historyLength);
	}

	public function logData($data){

		return parent::logData($data);
	}

	public function clearHistory(){

		return parent::clearHistory();
	}
}
?>