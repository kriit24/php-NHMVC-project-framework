<?
namespace Helper\Filter;

class Index extends \Library{

	public function __construct(){

		$this->Form = new Form;
		$this->inc( __DIR__.'/inc/script.js' );
		$this->inc( __DIR__.'/inc/style.css' );
	}

	//$filter->header( 'Form object' );
	//sets clickable headers
	public function header( $form ){

		$this->form = $form;
		$this->view('Filter-header');
	}

	//$filter->show( 'Form object' );
	//will shown filter on header
	public function show( $form ){

		$this->form = $form;
		$this->view('Filter-show');
	}

	//$filter->group( array('Form object', 'Form object') );
	//will show filter grouped
	public function group( $form ){

		if( !is_Array($form) )
			die('But group forms into array in:' . debug_backtrace(false, 1)[0]['file']);
		$this->form = $form;
		$this->view('Filter-group');
	}

	//$filter->group( array('Form object', 'Form object') );
	//will show filter grouped
	public function hiddenGroup( $form ){

		if( !is_Array($form) )
			die('But group forms into array in:' . debug_backtrace(false, 1)[0]['file']);
		$this->form = $form;
		$this->view('Filter-hidden-group');
	}
}


?>