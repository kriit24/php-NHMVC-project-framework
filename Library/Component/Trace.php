<?
namespace Library\Component;

class Trace{

	static function get( $limit = 0 ){

		ob_start();
		debug_print_backtrace(false, $limit);
		$trace = ob_get_contents();
		ob_end_clean();
		return $trace;
	}
}
?>