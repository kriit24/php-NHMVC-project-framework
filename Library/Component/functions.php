<?

function pre( $array ){

	print '<pre>';
	print_R ($array);
	print '</pre>';
}

function Table($name){

	$className = '\\'. \Conf\Conf::_DB_ROOT_DIR .'\\'. $name;
	return new $className;
}

//ifSwitch( !$log, 'done', 'hidden' );
function ifSwitch( $ifCondition, $return, $returnElse = '' ){

	if( $ifCondition === true )
		return $return;
	return $returnElse;
}

?>