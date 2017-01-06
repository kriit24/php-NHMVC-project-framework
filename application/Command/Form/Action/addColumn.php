<?
namespace Command\Form\Action;

abstract class addColumn{

	public static function init($data){

		$sessionData['column_type'] = $data['column_type'];
		$sessionData['column_name'] = $data['column_name'];
		$sessionData['column_label'] = $data['column_label'];

		\Session::formColumns( array($sessionData) );
	}
}
?>