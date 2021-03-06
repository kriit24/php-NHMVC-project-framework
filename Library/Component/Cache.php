<?
namespace Library\Component;

class Cache{

	const FILE_EXT = '.php';

	static function init(){

		if( !is_dir(get_include_path().'/tmp/cache/') )
			mkdir(get_include_path().'/tmp/cache', 0775, true);
	}

	static function exists($name){

		if( is_file(get_include_path().'/tmp/cache/'.$name.self::FILE_EXT) )
			return true;
		return false;
	}

	static function get($name){

		if( is_file(get_include_path().'/tmp/cache/'.$name.self::FILE_EXT) ){

			$fileContent = stripslashes( file_get_contents(get_include_path().'/tmp/cache/'.$name.self::FILE_EXT) );

			if( is_array($fileContentArray = json_decode($fileContent, true)) )
				$fileContent = $fileContentArray;
			return $fileContent;
		}
	}

	static function set($name, $content){

		if( !isset($name) || !isset($content) )
			return false;

		$fileContent = $content;
		if( is_array($fileContent) )
			$fileContent = json_encode($content);

		file_put_contents(get_include_path().'/tmp/cache/'.$name.self::FILE_EXT, addslashes($fileContent));
	}

	static function delete($name){

		if( is_file(get_include_path().'/tmp/cache/'.$name.self::FILE_EXT) )
			unlink(get_include_path().'/tmp/cache/'.$name.self::FILE_EXT);
	}
}

?>