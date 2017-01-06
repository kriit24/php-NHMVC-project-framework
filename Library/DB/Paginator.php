<?
namespace Library\DB;

class Paginator{

	public $start;
	public $limit;
	private $page;

	const PAGINATOR_GET = 'pagenumber';

	function __construct( $limit ){

		$this->start = 0;
		$this->limit = $limit;
		$this->page = is_numeric($_GET[self::PAGINATOR_GET]) ? $_GET[self::PAGINATOR_GET] : 0;

		$this->setLimit();
	}

	function setStmt($stmt, $params){

		$this->stmtArray = $stmt;
		$this->params = $params;
	}

	private function setLimit(){

		if( $this->page )
			$this->start = $this->limit*($this->page-1);
	}

	private function Query(){

		$sql = new \Library\Sql;
		$sql->setStmtArray($this->stmtArray);
		$sql->setParams($this->params);
		$sql->column(array('COUNT(1)' => 'rowsCount'));
		return $sql->fetchColumn('rowsCount');
	}

	function getPaginator(){

		$rowsCount = $this->Query();

		$page = $this->page > 5 ? ($this->page-5) : 1;
		$maxPages = round($rowsCount/$this->limit);

		$start = ($page < $maxPages ? $page : 1);
		$count = $maxPages > 10 ? 11 : $maxPages;
		if( (($count-1) + $page) > $maxPages )
			$start = $maxPages - ($count - 1);
		if( $maxPages > 1 )
			return array_fill($start, $count, $maxPages);
	}
}

?>