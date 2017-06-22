<?
namespace {namespace};

//ADD _Abstract if need to access over url

class Index extends Controller{

	public function __construct(){

		//$this->inc( __DIR__ . '/inc/script.js' );
		//$this->inc( __DIR__ . '/inc/style.css' );
		parent::__construct();
	}

	protected function Index(){

		$this->IndexData()->view('Index');
	}
}

?>