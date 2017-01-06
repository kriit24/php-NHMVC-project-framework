<?
if( !$this->command['-d'] ){

	$fileSystem = new \Library\FileSystem;

	echo "Folders:\n";
	echo "   ".implode("\n   ", $fileSystem->scandir(_DIR ."/". _APPLICATION_PATH, false, "", true))."\n";
	echo "Destination Folder: \e[93m";
	$folderName = trim(fgets(STDIN));
	echo "\e[0m";
	if( !is_dir(_DIR .'/'. _APPLICATION_PATH .'/'. $folderName) ){

		echo "\e[31mWrong destination folder [a-zA-Z] is required\e[0m\n";
		echo "Destination Folder: \e[93m";
		$folderName = trim(fgets(STDIN));
		echo "\e[0m";
		if( !is_dir(_DIR .'/'. _APPLICATION_PATH .'/'. $folderName) )
			die("\e[31mWrong destination folder\e[0m\n");
	}
	$this->command['-d'] = $folderName;
}

if( !$this->command['-t'] ){

	$templates = array_keys(\Command\Create\Form::CREATE);

	echo "Template name (".implode(", ", $templates)."): \e[93m";
	$templateName = ucfirst(trim(fgets(STDIN)));
	echo "\e[0m";
	if( !in_array($templateName, $templates) ){

		echo "\e[31mWrong template\e[0m\n";
		echo "Template name (".implode(", ", $templates)."): \e[93m";
		$templateName = trim(fgets(STDIN));
		echo "\e[0m";
		if( !in_array($templateName, $templates) )
			die("\e[31mWrong template\e[0m\n");
	}
	$this->command['-t'] = $templateName;
}

if( in_array($this->command['-t'], array('Method', 'Controller')) ){

	echo "Model Name: \e[93m";
	$modelName = trim(fgets(STDIN));
	echo "\e[0m";
	$this->command['-m'] = $modelName;
}


if( !$this->command['-c'] ){

	if( in_array($this->command['-t'], array('Method', 'Controller')) )
		echo "Method/Controller Name: \e[93m";
	else
		echo "Create App: \e[93m";

	$folderName = trim(fgets(STDIN));
	echo "\e[0m";
	$this->command['-c'] = $folderName;
}
?>