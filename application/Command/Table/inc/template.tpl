<?
namespace Table;

class {name} extends \Library\Sql{

	protected $_name = '{name}';
	protected $_validFields = array({fields});
	protected $_integerFields = array({indeger_fields});

	//LIKE TRIGGER, methods: Insert(data), Update(data, where), Delete(where)
	public function Insert( $data ){
		
		return parent::Insert( $data );
	}
}

?>