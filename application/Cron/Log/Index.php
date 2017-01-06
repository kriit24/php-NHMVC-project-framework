<?
namespace Cron\Log;

class Index extends Controller{

	const CRONTIME = '00 23 * * *';//minute, hour, day, monht, weekday
	const ESCAPE = false;//escape if allready loading

	public function __construct(){

		$this->control();
	}
}

?>