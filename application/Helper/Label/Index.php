<?
namespace Helper\Label;

class Index extends \Library{

	public function __construct( $label, $appendHtml = '' ){

		$this->label = $label;
		$this->appendHtml = $appendHtml;

		$this->view( 'Index' );
	}
}

?>