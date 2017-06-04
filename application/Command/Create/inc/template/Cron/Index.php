<?
namespace {namespace};

class Index extends Controller{

	const CRONTIME = '* * * * *';//minute, hour, day, month, weekday
	const ESCAPE = false;//escape if allready loading

	public function __construct(){

		echo __FILE__ . "\n";
		$this->control();
	}
}

?>