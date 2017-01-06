<?
namespace Command\Form\Action;

abstract class deleteColumn{

	public static function init($id){

		$sessionData = \Session::formColumns( true );
		unset($sessionData[$id]);
		$sessionData = array_values($sessionData);
		\Session::clear( 'formColumns' );

		\Session::formColumns( $sessionData );
	}
}
?>