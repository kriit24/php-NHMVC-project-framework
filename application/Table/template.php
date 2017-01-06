<?
namespace Table;

class template extends \Library\Sql{

	protected $_name = 'template';
	protected $_validFields = array('id', 'name', 'language', 'content', 'subject', 'from_name', 'from_email', 'file', 'is_active');
	protected $_integerFields = array('id', 'is_active');
}

?>