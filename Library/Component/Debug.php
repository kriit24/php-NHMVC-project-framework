<?
namespace Library\Component;

class Debug{

	static function trace($limit = 1){

		$array = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, $limit + 1 );
		unset($array[0]);
		return array_values($array);
	}
}

?>