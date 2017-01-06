<?
//# 1
//how to set filter for QUERY: view examples/DB/paginate

//# 2
//add to index construct
$p = new \Helper\Paginator;
//add to view
$p->paginate( $this->client/*db query object*/ );

?>