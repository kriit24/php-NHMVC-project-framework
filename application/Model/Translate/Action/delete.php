<?
namespace Model\Translate\Action;

abstract class delete{

	public static function init(){

		/*
		pre($_GET);
		pre($_POST);
		*/

		$name = \Table\translate::singleton()->fetchColumn( 'name', array('id' => $_GET['id']) );

		if( $name )
			\Table\translate::singleton()->Delete( array('name LIKE ? ' => $name) );
	}
}
?>