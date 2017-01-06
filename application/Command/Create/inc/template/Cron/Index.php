<?
namespace {namespace};

class Index extends Controller{

	const CRONTIME = '* * * * *';//minute, hour, day, monht, weekday
	const ESCAPE = false;//escape if allready loading

	public function __construct(){

		echo __FILE__;
		$this->control();
	}
}

?>