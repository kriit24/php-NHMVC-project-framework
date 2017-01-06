<?
namespace Library;

class Replace{

	use \Library\Component\Singleton;

	function replace($content, $array){

		return \Library\Component\Replace::replace($content, $array);
	}
}
?>