<?
namespace Model\Menu;

class Controller extends \Library{

	public function addMethod(){

		pre($_POST);
	}

	public function getDataList(){

		$this->crons = glob(_DIR .'/application/Cron/*', GLOB_ONLYDIR);
		return $this;
	}
}

?>