<?
namespace Table;

class route extends \Library\Sql{

	protected $_name = 'route';
	protected $_validFields = array('id', 'parent_id', 'canonical_url', 'logical_url', 'is_indexed', 'is_deleted', 'created_at');
	protected $_integerFields = array('id', 'parent_id', 'is_indexed', 'is_deleted');
}

?>