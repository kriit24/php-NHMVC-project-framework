<?
namespace Table;

class {name} extends \Library\Sql{

	protected $_name = '{name}';
	protected $_validFields = array({fields});
	protected $_integerFields = array({indeger_fields});
	//protected $_aliasFields = array("DATE_FORMAT(created_at, '%d.%m.%Y')" => 'created_at_str');
	//protected $_trigger = array('INSERT' => 'insertBefore', 'UPDATE' => 'updateBefore');

	protected function insertBefore( $data ){
	}

	protected function updateBefore( $data, $where ){
	}
}

?>