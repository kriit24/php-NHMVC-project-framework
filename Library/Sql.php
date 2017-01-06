<?
namespace Library;

class Sql extends Extension\Sql{

	use \Library\Component\Singleton;

	protected $_name = '';
	protected $_validFields = array('*');
	protected $_integerFields = array('*');

	function __clone(){

		//$this->Fetch = $this->PDO->Fetch();
		//$this->Statement = $this->PDO->Statement();
	}

	public function __construct( $connName = '' ){

		$this->_connName = ($connName ?: $this->_connName);
	}

	public function addslashes( $data ){

		return $this->phpFunction( 'addslashes', $data );
	}

	public function stripslashes( $data ){

		return $this->phpFunction( 'stripslashes', $data );
	}

	public function htmlspecialchars( $data ){

		return $this->phpFunction( 'htmlspecialchars', $data );
	}

	//->paginator(5)
	function paginator( $limit ){

		$paginator = new DB\Paginator( $limit );
		$paginator->setStmt( $this->getStmtArray(), $this->getParams() );
		$this->paginator = $paginator->getPaginator();

		return $this->limit($paginator->start, $paginator->limit);
	}

	//->filter(array('first_name' => 'LIKE %?%', 'last_name' => 'LIKE %?%', 'is_temporary' => 'IN(?)', 'changed_at' => 'BETWEEN', 'city'), $_GET)
	function filter($where, $data){

		return $this->where(DB\Filter::getWhere($where), $data);
	}

	//->sort(array('first_name', 'last_name'), $_GET['sort'])
	function sort($columns, $sort){

		$this->sort = $columns;
		$sortBy = DB\Sort::getSort($sort);
		if( $sortBy )
			$this->order($sortBy);
		return $this;
	}

	static function end($autoloader){

		parent::close();
	}
}

?>