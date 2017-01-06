<?
namespace Table;

class log extends \Library\Sql{

	protected $_name = 'log';
	protected $_validFields = array('id','table_name','table_id','action','data','created_by','created_by_id','created_at');
	protected $_integerFields = array('id', 'table_id', 'created_by_id');
}

?>