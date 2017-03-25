<?
return $this->Select()
->column(
	array($this->jsonColumn( array('t2.action_id', 't2.value') ) => 'json_data')//result will be GROUP_CONCAT() AS json_data
)
->from( 'table AS t1 ' )
->leftjoin(
	"table", "t2", "t2.related_id = t1.id"
)
->where( $filter )
->group('t1.id');
?>