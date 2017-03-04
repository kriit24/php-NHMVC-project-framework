<?
namespace Helper\Paginator;

class Index extends \Library{

	public function __construct(){

		$this->inc( __DIR__.'/inc/style.css' );
	}

	public function paginate( $paginator ){

		if( !empty($paginator->paginator) ){

			$this->pages = $paginator->paginator;
			$this->view('Paginate');
		}
	}
}

?>