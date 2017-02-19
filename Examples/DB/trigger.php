<?

//EASY TRIGGER
class tableName extends \Library\Sql{

	protected $_trigger = array('INSERT' => 'insertBefore', 'UPDATE' => 'updateBefore');

	public function insertBefore( $data ){

		$data['created_by'] = \Library\Session::userData()->user_id;

		return $data;
	}

	public function updateBefore( $data, $where ){

		$data['updated_by'] = \Library\Session::userData()->user_id;

		return $data;
	}
}

?>