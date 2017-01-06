<?
namespace Package;

class packageName{

	function __construct(){

		//if package have autoloader
		\Autoload::unregister();

		require __DIR__.'/pakacgename/packagefile.php';
	}

	function __destruct(){

		//if package have autoloader
		\Autoload::register();
	}
}

?>