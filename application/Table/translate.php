<?
namespace Table;

class translate extends \Library\Sql{

	protected $_name = 'translate';
	protected $_validFields = array('id', 'name', 'value', 'language', 'model');
	protected $_integerFields = array('id');
}

?>