<?
namespace {namespace};

class Index extends Controller{

	public function __construct(){

		//$this->inc( $this->toUrl(__DIR__) . '/inc/script.js' );
		//$this->inc( $this->toUrl(__DIR__) . '/inc/style.css' );
	}

	protected function Index(){

		$this->getData()->view('Index');
	}
}

?>