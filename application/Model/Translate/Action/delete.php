<?
namespace Model\Translate\Action;

abstract class delete{

	public static function init(){

		/*
		pre($_GET);
		pre($_POST);
		*/

		$name = \Table\language::singleton()->fetchColumn( 'name', array('id' => $_GET['id']) );

		if( $name )
			\Table\language::singleton()->Delete( array('name LIKE ? ' => $name) );
	}
}
?>