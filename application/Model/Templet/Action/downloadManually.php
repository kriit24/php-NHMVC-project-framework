<?
namespace Model\Templet\Action;

abstract class downloadManually{

	public static function init(){

		$linkFrom = base64_decode($_GET['file']);
		$linkTo = base64_decode($_GET['file_to']);
		list($fileName, ) = explode('?', $linkTo);
		list($fileName, ) = explode('#', $fileName);

		if( \Session::designErrors() ){

			$errors = \Session::designErrors(true);
			foreach($errors as $k => $v){

				if( $v['get_from'] == $linkFrom )
					unset( $errors[$k] );
			}
			\Session::clear('designErrors');
			\Session::designErrors($errors);
		}

		$content = '';
		if( $linkFrom )
			$content = \Library\Curl::get($linkFrom);
		
		if( strlen($content) > 0 ){

			if( !is_dir( dirname(_DIR . $fileName) ) )
				mkdir( dirname(_DIR . $fileName), 0755, true );

			if( pathinfo(basename($fileName), PATHINFO_EXTENSION) == 'css' ){

				//@IMPORT;
				preg_match_all('/import([\s_]+)\"(.*?)\"/s', $content, $matches);
				$links2 = \Model\Templet\Action\updateDesign::getLink( array($matches[0]), '', 'import', $links2, dirname($fileName), $fileName );

				//URL(
				preg_match_all('/url\((.*?)\)/s', $content, $matches);
				$links2 = \Model\Templet\Action\updateDesign::getLink( array($matches[1]), '', 'url', $links2, dirname($fileName), $fileName );

				$content = \Model\Templet\Action\updateDesign::getLinksData($links2, $content);
			}

			file_put_contents( _DIR . $fileName, $content );
		}

		die(\Library\Http::redirect( \Library::singleton()->url( array('model' => 'Templet') ) .'#tabs-style' ));
	}
}
?>