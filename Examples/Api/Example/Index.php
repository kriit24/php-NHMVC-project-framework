<?
namespace Api\Example;

class Index extends Controller{

	function __construct(){

		//$this->inc( $this->uri(__DIR__) . '/inc/script.js' );
		//$this->inc( $this->uri(__DIR__) . '/inc/style.css' );

		$this->Form = new Form;
	}

	function Example(){

		if( $this->Form->isValidExample() )
			$this->POST(FORM::SUBMIT['add'])->action();

		$this->getData()->view('Example');
	}

	function Dashboard_Title(){

		$this->view('Dashboard_Title');
	}

	function Dashboard_Menu(){

		$this->view('Dashboard_Menu');
	}

	function Dashboard(){

		$this->view('Dashboard');
	}
}

?>