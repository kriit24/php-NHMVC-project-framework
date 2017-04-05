<?
namespace {namespace};

class Controller extends \Library{

	public function __construct(){

		$this->{table} = new \Table\{table};
	}

	public function getData(){

		$this->{table}->Select();

		return $this;
	}
}

?>