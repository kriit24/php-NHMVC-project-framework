_Autoload is directory first loader if file not found then it takes no action for that directory

create dir: HelloWorld into application/
create autoload filename: _Autoload.php
create HelloWorld file: HelloWorld.php

_Autoload.php content
<?
new \HelloWorld\HelloWorld();//filename is also classname
?>

HelloWorld.php content
<?
namespace HelloWorld;

class HelloWorld{

	function __construct(){

		echo 'HelloWorld';
	}
}
?>

also it can include subfolders view Example/Api