<?
namespace Cron\getLog;

class Index extends Controller{

	const CRONTIME = '* * * * *';//minute, hour, day, month, weekday
	const ESCAPE = true;

	public function __construct(){

		if( $_GET['cron'] == 'getLog' )
			$this->getLog()->view('Index');
	}
}

?>