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
		$sql_1 = new \Library\Sql;
		$sql->setStmtArray($this->stmtArray);
		$sql->setParams($this->params);
		$sql->column(" 1 AS count ");
		$qString = $sql->getQuery();

		$sql_1->Query( " SELECT COUNT(1) AS rowsCount FROM ( " . $qString ." ) AS a" );
		return $sql_1->fetchColumn('rowsCount');
	}

	function getPaginator(){

		$rowsCount = $this->Query();
		$this->rowsCount = $rowsCount;

		$page = $this->page > 5 ? ($this->page-5) : 1;
		$maxPages = round($rowsCount/$this->limit);

		$start = ($page < $maxPages ? $page : 1);
		$count = $maxPages > 10 ? 11 : $maxPages;
		if( (($count-1) + $page) > $maxPages )
			$start = $maxPages - ($count - 1);
		if( $maxPages > 1 )
			return array_fill($start, $count, $maxPages);

		if( $maxPages == 1 && $rowsCount > $this->limit )
			return array_fill($start, 2, 2);
	}
}

?>