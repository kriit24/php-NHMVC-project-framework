<?
namespace Helper\Form;

class Index extends Controller{

	use \Library\Component\Singleton;

	public function __construct(){
	}

	public function Label( $label, $style = '', $appendHtml = '' ){

		$this->inc( __DIR__ . '/inc/style.css' );

		$this->label = $label;
		$this->style = $style;
		$this->appendHtml = $appendHtml;

		$this->view( 'Label' );
	}
}

?>