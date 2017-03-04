<?
namespace Model\Language;

class Index extends \Library{

	public function __construct(){

		//$this->inc( __DIR__ . '/inc/script.js' );
		$this->inc( __DIR__ . '/inc/style.css' );
	}

	protected function Index(){

		$this->view('Index');
	}

	protected function Index_Admin(){

		$this->view('Index');
	}
}

?>