<?
namespace Model\UserInterface;

class Index extends \Library{

	public function __construct(){
	}

	public function Dashboard(){

		$this->view('Dashboard');
	}
}

?>