<?
namespace Table;

class language extends \Library\Sql{

	protected $_name = 'language';
	protected $_validFields = array('id', 'name', 'value', 'language', 'model');
	protected $_integerFields = array('id');
}

?>