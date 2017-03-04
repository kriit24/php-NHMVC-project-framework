<?
namespace Model\Dashboard;

class Index extends Controller{

	public $Title = '';
	public $Links = array();
	public $ShowDashboard = false;
	public $GoWithScroll = false;

	use \Library\Component\Singleton;

	public function __construct(){

		$this->inc( __DIR__.'/inc/style.css' );
		if( \Library\Browser::get()['short'] == 'IE' )
			$this->inc( __DIR__.'/inc/style-ie.css' );
		if( !$_GET['route'] )
			$this->inc( '/Template/css/clear-content-bar.css' );
		//$this->inc( __DIR__ .'/inc/script.js' );
	}

	//THIS header is for title
	protected function Header(){
	}

	public function title($title = '', $links = array(), $showDashboard = true, $goWithScroll = false){

		$this->Title = $title;
		$this->Links = $links;
		$this->ShowDashboard = $showDashboard;
		$this->GoWithScroll = $goWithScroll;
		$this->view( 'Title');
	}

	protected function Index(){

		$this->getModels( array('Loan') )->view('Index');
	}

	protected function Index_Admin(){

		$this->getModels()->view('Index_Admin');
	}
}

?>