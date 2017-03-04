<?
namespace Model\Menu;

class Index extends Controller{

	public function __construct(){

		$this->inc( __DIR__ . '/inc/script.js' );
		$this->inc( __DIR__ . '/inc/style.css' );

		//$this->Form = new Menu_Form;
	}

	protected function Index(){

		$this->view('Public');
	}

	protected function Index_Admin(){

		$this->view('Admin');
	}

	protected function Submenu( $name ){

		if( is_file(__DIR__.'/view/submenu/'.$name.'.php') )
			$this->view('submenu/'.$name);
	}
}

?>