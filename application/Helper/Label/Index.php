<?
namespace Helper\Label;

class Index extends \Library{

	//\Helper\Label::label( 'Name', 'style', 'appendhtml' );

	public static function Label( $label, $style = '', $appendHtml = '' ){

		$this->inc( __DIR__ . '/inc/style.css' );

		$this->label = $label;
		$this->style = $style;
		$this->appendHtml = $appendHtml;

		$this->view( 'Label' );
	}
}

?>