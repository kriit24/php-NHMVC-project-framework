<?
namespace Model\Templet\Action;

abstract class uploadZip{

	public static function init(){

		$file = \Library\FileSystem::singleton()->uploadFile( 'design_zip', array('zip'), _DIR .'/tmp/design/' );
		if( $file ){

			$loc = _DIR .'/' . _APPLICATION_PATH .'/Template/' . \Conf\Conf::_TEMPLATE . '/public/ext';
			$zip = new \Library\Zip;
			$zip->extract( $file, $loc );

			if( !is_file($loc . '/index.html') )
				return new \Library\Component\Error('index.html is missing', '', true);

			$content = file_Get_contents( $loc . '/index.html' );
			preg_match('/(.*?)<\/head>/s', $content, $match);
			$header_php = $match[0];

			preg_match('/<body(.*?)<\/body>/s', $content, $match);
			$body_php = $match[0];

			preg_match('/\<\/body\>(.*?)$/s', $content, $match);
			$footer_php = $match[1];

			$dir = dirname($loc) .'/design';
			file_put_Contents($dir . '/header-pre.php', $header_php);
			file_put_Contents($dir . '/body-pre.php', $body_php);
			file_put_Contents($dir . '/footer-pre.php', $footer_php);
		}
	}
}
?>