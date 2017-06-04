<?
namespace Model\Templet\Action;

abstract class updateFiles{

	public static function init(){

		$HTTP_POST = \Library\Http::POST();

		$i = 0;
		foreach($_POST['files'] as $key => $file){

			$content = stripslashes(stripslashes(str_replace(array("\\r","\\n"), array("\r","\n"), $HTTP_POST['content'][$i])));
			$content = preg_replace('/\{([a-zA-Z0-9\_\-_]+)\}/s', '{[\\1]}', $content);
			
			$dir = _DIR .'/'. _APPLICATION_PATH .'/Template/' . \Conf\Conf::_TEMPLATE .'/public';
			$file = str_replace( '/Template/public', '', $file );

			if( is_File($dir . $file) )
				file_put_contents($dir . $file, $content);
			else{

				$oldFile = str_replace( '/Template/public', '', $_POST['file_name'][$key]);

				unlink($dir . $oldFile);
				file_put_contents($dir . $file, $content);
			}
			$i++;
		}
	}
}

?>