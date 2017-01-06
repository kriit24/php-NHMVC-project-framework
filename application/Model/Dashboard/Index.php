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

	protected function Index(){

		//$this->getModels()->view('Index');
		echo 'Dashboard Index';
	}

	protected function Index_Admin(){

		$this->getModels()->view('Index_Admin');
	}
}

?>