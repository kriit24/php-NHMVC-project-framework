<?
namespace Command\Create\Action;

abstract class replace{

	public static function init($content, $array){

		return preg_replace_callback('/\{([a-zA-Z0-9\_[:space:]_]+)\}/', function( $matches ) use ( $array ) {return $array[$matches[1]];}, $content);
	}
}
?>