<?

function pre( $array ){

	if( !is_array($array) && !is_object($array) ){

		echo '<pre>'.$array.'</pre>';
	}
	else{

		print '<pre>';
		print_R ($array);
		print '</pre>';
	}
}

function _trim( $textOrArray ){

	if( !is_Array($textOrArray) )
		$textOrArray = array($textOrArray);

	$trim = array();
	foreach($textOrArray as $k => $v){

		if( is_array($v) ){

			$trim[$k] = _trim($v);
		}
		else{

			$trim[$k] = trim($v);
		}
	}

	return $trim;
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