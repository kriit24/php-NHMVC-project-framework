<?
namespace Helper\Sort;

class Index extends \Library{

	public function __construct(){

		$this->inc( __DIR__.'/inc/style.css' );
		$this->inc( __DIR__.'/inc/script.js' );
	}

	public function Sort($sort){

		$this->sort = $sort->sort;
		$this->view('Sort');
	}
}

?>