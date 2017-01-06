<?
namespace Model\Page;

class Index extends Controller{

	public function __construct(){

		$this->inc( $this->toUrl(__DIR__) .'/inc/style.css' );
	}

	protected function Index(){

		$this->view( $this->getView() );
	}
}

?>