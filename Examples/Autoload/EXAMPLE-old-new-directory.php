<?

//if someone uses oled class name with old location, then make old class what extends new class with new location

new \test1\test();//old
new \test2\test();//new

//old
namespace test1;
//old class will extend new class
class test extends \test2\test{

	function __construct(){

		echo test1;

		parent::__construct();
	}
}

//new
namespace test2;

class test{

	function __construct(){

		echo test2;
	}
}

?>