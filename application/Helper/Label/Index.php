<?
namespace Helper\Label;

class Index extends \Library{

	public function __construct( $label, $appendHtml = null ){

		$this->inc( __DIR__ . '/inc/script.js' );

		$this->label = $label;
		$this->appendHtml = $appendHtml;

		$this->view( 'Index' );
	}
}

?>