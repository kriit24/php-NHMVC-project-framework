<?
namespace Command\Form\Action;

abstract class updateColumn{

	public static function init($data){

		$sessionData = \Session::formColumns( true );
		\Session::clear( 'formColumns' );
		//pre($sessionData);

		foreach($data['column_type'] as $k => $v){

			$column_type = $v;
			$column_name = $data['column_name'][$k];
			$column_label = $data['column_label'][$k];

			$sessionData[$k]['column_type'] = $column_type;
			$sessionData[$k]['column_name'] = $column_name;
			$sessionData[$k]['column_label'] = $column_label;
		}
		//pre($sessionData);
		\Session::formColumns( $sessionData );
	}
}
?>