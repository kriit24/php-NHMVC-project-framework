<?
if( !$this->command['-a'] ){

	$actions = \Command\Table\Index::ACTIONS;

	echo "Action (".implode(", ", $actions)."): \e[93m";
	$actionName = ucfirst(trim(fgets(STDIN)));
	echo "\e[0m";
	if( !in_array($actionName, $actions) ){

		echo "\e[31mWrong action\e[0m\n";
		echo "Action (".implode(", ", $actions)."): \e[93m";
		$actionName = trim(fgets(STDIN));
		echo "\e[0m";
		if( !in_array($actionName, $actions) )
			die("\e[31mWrong action\e[0m\n");
	}
	$this->command['-a'] = $actionName;
}
if( !$this->command['-t'] ){

	echo "Tablename: \e[93m";
	$tableName = trim(fgets(STDIN));
	echo "\e[0m";
	$this->command['-t'] = $tableName;
}
?>