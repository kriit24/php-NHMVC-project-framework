<?

//all in Library can call as self extension
//class will be called over __call magic method
class Url{

	function url(){
	}

	function uri(){
	}
}

//isPrivate means that class cannot call over __call magic method as self extension
class Ftp extends isPrivate{

	function ftp(){
	}
}

//EXAMPLE
class Model extends \Library{

	function __construct(){

		$this->url();//directly call class Url, method url, if class extends \isprivate then will not be called
		$ftp = new \Library\Ftp;//if cannot call directly
	}
}

?>