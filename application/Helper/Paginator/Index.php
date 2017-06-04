<?
namespace Helper\Paginator;

class Index extends \Library{

	public function __construct(){

		$this->inc( __DIR__.'/inc/style.css' );
	}

	public function paginate( $paginator, $showCount = true ){

		if( $showCount == false && empty($paginator->paginator) )
			return;

		//if( !empty($paginator->paginator) ){

			$this->q = $paginator;
			$this->pages = $paginator->paginator;
			$this->showCount = $showCount;
			$this->view('Paginate');
		//}
	}
}

?>