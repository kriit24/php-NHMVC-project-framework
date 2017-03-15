<?
namespace Library\Component;

class Replace{

	static function replace($content, $array, $replaceIfEmpty = true){

		//multi replacement
		$content = preg_replace_callback('/\{([a-zA-Z0-9\__]+)\}(.*?)\{\/\\1\}/s', function( $matches ) use ( $array, $replaceIfEmpty ) {
			
			$key = $matches[1];
			$html = $matches[2];
			$list = '';
			if( is_array($array[$key]) ){

				foreach($array[$key] as $row)
					$list .= \Library\Component\Replace::replace($html, $row, $replaceIfEmpty);
			}
			return $list;
		}, $content);

		$content = preg_replace_callback('/\{([a-zA-Z0-9\__]+)\}/', function( $matches ) use ( $array, $replaceIfEmpty ) {

			if( $replaceIfEmpty )
				return $array[$matches[1]];
			else
				return isset($array[$matches[1]]) ? $array[$matches[1]] : $matches[0];
		}, $content);
		return preg_replace_callback('/\<\?PHP(.*?)\?\>/s', function( $matches ) use ( $array ) {
			
			return self::evalReplace(self::replace($matches[1], $array), $array);
		}, $content);
	}

	static function editorReplace( $content ){

		$content = preg_replace('/\{\[([a-zA-Z0-9\_\-\:\/_]+)\]\}/s', '{\\1}/', stripslashes(str_replace(array("\\r","\\n"), array("\r","\n"), $content)));
		$content = preg_replace('/\<\!\-\-\{([a-zA-Z0-9\_\-\:\/_]+)\}\-\-\>/s', '{\\1}', $content);
		return $content;
	}

	private static function evalReplace($content, $array){
		
		ob_start();
		eval($content);
		$eval = ob_get_contents();
		ob_end_clean();
		return $eval;
	}

	static function replacenl($content){

		return str_replace(	array("\r\n", "\r", "\n", "\t"), "", $content );
	}

	static function correctnl($content){

		return stripslashes(str_replace(array("\\r","\\n"), array("\r","\n"), $content));
	}
}

?>