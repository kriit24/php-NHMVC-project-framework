<?
namespace Model\Dashboard;

class Index extends Controller{

	public $Title = '';
	public $Links = array();
	public $ShowDashboard = false;
	public $GoWithScroll = false;

	use \Library\Component\Singleton;

	public function __construct(){

		$this->inc( $this->toUrl(__DIR__).'/inc/style.css' );
		if( \Library\Browser::get()['short'] == 'IE' )
			$this->inc( $this->toUrl(__DIR__).'/inc/style-ie.css' );
		if( !$_GET['route'] )
			$this->inc( '/Template/css/clear-content-bar.css' );
		//$this->inc( $this->toUrl(__DIR__) .'/inc/script.js' );
	}

	public function title($title = '', $links = array(), $showDashboard = true, $goWithScroll = false){

		$this->Title = $title;
		$this->Links = $links;
		$this->ShowDashboard = $showDashboard;
		$this->GoWithScroll = $goWithScroll;
		$this->view( 'Title');
	}

	protected function Header(){
	}

	protected function Index(){

		$this->getModels()->view('Index');
	}
}

?>