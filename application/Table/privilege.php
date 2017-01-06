<?
namespace Table;

class privilege extends \Library\Sql{

	protected $_name = 'privilege';
	protected $_validFields = array('id', 'role_id', 'route', 'class', 'method');
	protected $_integerFields = array('id', 'role_id');
}

?>