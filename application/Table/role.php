<?
namespace Table;

class role extends \Library\Sql{

	protected $_name = 'role';
	protected $_validFields = array('id', 'name', 'description', 'is_enabled', 'created_at', 'level', 'type');
	protected $_integerFields = array('id', 'is_enabled', 'level');

	public function getForSelect(){

		return $this->Select()
		->column(array('id', 'name'))
		->where("level <= ".\Session::userData()->level)
		->order("level")
		->fetchAll();
	}
}

?>