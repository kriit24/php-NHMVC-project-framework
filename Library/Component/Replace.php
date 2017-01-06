<?
namespace Library\Component;

class Replace{

	static function replace($content, $array){

		$content = preg_replace_callback('/\{([a-zA-Z0-9\__]+)\}/', function( $matches ) use ( $array ) {return $array[$matches[1]];}, $content);
		return preg_replace_callback('/\<\?PHP(.*?)\?\>/s', function( $matches ) use ( $array, $self ) {return self::evalReplace(self::replace($matches[1], $array), $array);}, $content);
	}

	private static function evalReplace($content, $array){

		ob_start();
		eval($content);
		$eval = ob_get_contents();
		ob_end_clean();
		return $eval;
	}
}

?>