<?
namespace Cron\Log;

class Controller{

	protected function Control(){

		//controller content
		\Library\Log::singleton()->clearHistory();
	}
}

?>