<?
namespace Cron\Example;

class Index extends Controller{

	const CRONTIME = '* * * * *';

	function __construct(){

		echo 'CRON INDEX='.__FILE__.'<br>';
		$this->control();
	}
}

?>