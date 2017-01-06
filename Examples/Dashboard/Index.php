<?
namespace Model\Example;

class Index extends Controller{

	//will be show by privilege
	//dashboard content will be shown only in firstpage
	function Dashboard(){

		$this->view('Dashboard');
	}
}

?>