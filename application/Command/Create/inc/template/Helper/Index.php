<?
namespace {namespace};

class Index extends Controller{

	public function __construct(){

		//$this->inc( __DIR__ . '/inc/script.js' );
		//$this->inc( __DIR__ . '/inc/style.css' );
		parent::__construct();
	}

	protected function Index(){

		$this->getData()->view('Index');
	}
}

?>