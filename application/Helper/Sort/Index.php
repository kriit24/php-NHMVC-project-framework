<?
namespace Helper\Sort;

class Index extends \Library{

	public function __construct(){

		$this->inc( $this->toUrl(__DIR__).'/inc/style.css' );
		$this->inc( $this->toUrl(__DIR__).'/inc/script.js' );
	}

	public function Sort($sort){

		$this->sort = $sort->sort;
		$this->view('Sort');
	}
}

?>