<?
namespace Cron\DBBackup;

class Index extends Controller{

	const CRONTIME = '* 01,02 * * *';//minute, hour, day, month, weekday
	const ESCAPE = false;

	public function __construct(){

		$this->control();
	}
}

?>