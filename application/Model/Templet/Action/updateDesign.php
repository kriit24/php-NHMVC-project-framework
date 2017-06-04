<?
namespace Model\Templet\Action;

abstract class updateDesign{

	static $DO_NOT_DOWNLOAD = array(
		'google', 'pubads.g.doubleclick.net', 'www.doubleresults.com', 'oss.maxcdn.com'
	);

	public static function init(){

		$HTTP_POST = \Library\Http::POST();
		\Session::clear('designErrors');

		if( $_POST['from_url'] ){

			$content = file_get_contents( $_POST['from_url'] );
			if( empty($content) )
				$content = \Library\Curl::get( $_POST['from_url'] );

			$content = iconv('windows-1252', 'UTF-8', $content);
		}
		else{

			//$content = \Library\Replace::correctnl($HTTP_POST['content']);
			$content = $HTTP_POST['content'];
		}

		$content = str_replace("\/", "-{\/}-", $content);
		$content = \Library\Replace::correctnl($content);
		$content = str_replace( array('-{/}-', '-{\/}-'), '\/', $content );
		//$content = $HTTP_POST['content'];

		$content = preg_replace("/([a-zA-Z-_]+)='(.*?)'/s", '\\1="\\2"', $content);

		$links = self::getLinks( $content );
		$content = self::getLinksData($links, $content);

		self::getLinksFromCssContent( $links );

		//die(pre($links));
		//die(pre(htmlspecialchars($content)));

		preg_match('/(.*?)<\/head>/s', $content, $match);
		$header_php = $match[0];

		preg_match('/<body(.*?)<\/body>/s', $content, $match);
		$body_php = $match[0];

		preg_match('/\<\/body\>(.*?)$/s', $content, $match);
		$footer_php = $match[1];

		$dir = _DIR .'/' . _APPLICATION_PATH .'/Template/'. \Conf\Conf::_TEMPLATE .'/public/design';

		if( file_exists($dir . '/header-pre.php') )
			unlink($dir . '/header-pre.php');
		
		if( file_exists($dir . '/body-pre.php') )
			unlink($dir . '/body-pre.php');

		if( file_Exists($dir . '/footer-pre.php') )
			unlink($dir . '/footer-pre.php');

		file_put_Contents($dir . '/header-pre.php', $header_php);
		file_put_Contents($dir . '/body-pre.php', $body_php);
		file_put_Contents($dir . '/footer-pre.php', $footer_php);

		\Library\Component\Cache::delete('template');

		//die(\Library\Http::redirect('?action=updatedesign'));
	}

	private static function getLinksFromCssContent( $links ){

		$links2 = array();

		if( !empty($links) ){

			//import
			foreach($links as $link){

				if( pathinfo(basename($link['new']), PATHINFO_EXTENSION) == 'css' ){

					$isDir = _DIR . dirname($link['new']);
					list($fileName, ) = explode('?', basename($link['new']));
					list($fileName, ) = explode('#', $fileName);

					if( !is_file($isDir . '/' . $fileName) ){

						$contentCss = file_Get_contents( $link['get_from'] );
					}
					else{

						$contentCss = file_Get_contents( $isDir . '/' . $fileName );
					}
					
					//@IMPORT;
					preg_match_all('/import([\s_]+)\"(.*?)\"/s', $contentCss, $matches);
					$links2 = self::getLink( array($matches[0]), '', 'import', $links2, dirname($link['get_from']), $link['get_from'] );
					
					//URL(
					preg_match_all('/url\((.*?)\)/s', $contentCss, $matches);
					$links2 = self::getLink( array($matches[1]), '', 'url', $links2, dirname($link['get_from']), $link['get_from'] );

					$contentCss = self::getLinksData($links2, $contentCss);

					if( !is_dir($isDir) ){

						mkdir($isDir, 0755, true);
					}

					file_put_contents( $isDir . '/' . $fileName, $contentCss);
				}
			}
		}
		//die(pre($links2));
	}

	private static function getLinks( $content ){

		$links = array();

		preg_match_all('/<link(.*?)\>/s', $content, $matches);
		$links = self::getLink( $matches, 'href=', 'css', $links, $_POST['from_url'] );

		//css from content
		preg_match_all('/url\((.*?)\)/s', $content, $matches);
		$links = self::getLink( array($matches[1]), '', 'url', $links, $_POST['from_url'] );

		preg_match_all('/<script(.*?)\>/s', $content, $matches);
		$links = self::getLink( $matches, 'src=', 'script', $links, $_POST['from_url'] );

		preg_match_all('/<img(.*?)\>/s', $content, $matches);
		$links = self::getLink( $matches, 'src=', 'img', $links, $_POST['from_url'] );
		return $links;
	}

	public static function getLinksData( $links, $content ){

		$errors = array();
		if( \Session::designErrors() )
			$errors = \Session::designErrors( true );

		foreach($links as $link){

			$content = self::replaceLinkLocation($content, $link);

			$isDir = _DIR . dirname($link['new']);
			list($fileName, ) = explode('?', basename($link['new']));
			list($fileName, ) = explode('#', $fileName);

			if( !is_file($isDir . '/' . $fileName) ){

				$fileContent = @file_Get_contents($link['get_from']);

				if( !preg_match('/forbidden/i', $fileContent) && !preg_match('/404/i', $fileContent) && !preg_match('/HTTP request failed!/i', $fileContent) && strlen($fileContent) > 0 ){

					if( !is_dir($isDir) ){

						mkdir($isDir, 0755, true);
					}

					if( pathinfo(basename($link['new']), PATHINFO_EXTENSION) )
						file_put_contents( $isDir . '/' . $fileName, $fileContent );
				}
				else{

					if( $link && !in_array($link, $errors) )
						$errors[] = $link;
				}
			}
		}

		if( isset($errors) )
			\Session::designErrors( $errors );
		return $content;
	}

	private static function replaceLinkLocation($content, $link){

		return str_replace($link['orig'], $link['new'], $content);

		$modifier = str_replace(array('/', '?', '-'), array('\/', '\?', '\-'), $link['elem'] . '"' . $link['orig'] .'"');
		if( preg_match('/'. $modifier .'/i', $content) ){

			return str_replace($link['elem'] . '"' . $link['orig'] .'"', $link['elem'] . '"' . $link['new'] .'"', $content);
		}

		$modifier = str_replace(array('/', '?', '-'), array('\/', '\?', '\-'), $link['elem'] . '\'' . $link['orig'] .'\'');
		if( preg_match('/'.$modifier.'/i', $content) ){

			return str_replace($link['elem'] . '\'' . $link['orig'] .'\'', $link['elem'] . '\'' . $link['new'] .'\'', $content);
		}

		$modifier = str_replace(array('/', '?', '-'), array('\/', '\?', '\-'), $link['elem'] . '' . $link['orig'] .'');
		if( preg_match('/'.$modifier.'/i', $content) ){

			return str_replace($link['elem'] . '' . $link['orig'] .'', $link['elem'] . '' . $link['new'] .'', $content);
		}

		$modifier = str_replace(array('/', '?', '-'), array('\/', '\?', '\-'), '^'.$link['elem'] . '' . $link['orig'] .'$');
		if( preg_match('/'.$modifier.'/i', $content) ){

			return str_replace($link['elem'] . '' . $link['orig'] .'', $link['elem'] . '' . $link['new'] .'', $content);
		}
		return $content;
	}

	public static function getLink( $matches, $elem, $type, $links, $getFrom = '', $sourceFile = '' ){

		if( empty($matches) )
			return array();

		$template = _APPLICATION_PATH . '/Template/' . \Conf\Conf::_TEMPLATE . '/public';
		$extLocation = array(
			'ext' => '/ext/',
		);

		foreach($matches[0] as $match){

			if( preg_match('/data\:image/i', $match) )
				continue;

			self::getSiteLocalLinks( $match, $elem, $type, $template, $extLocation, $links, $getFrom, $sourceFile );
			self::getSiteRemoteLinks( $match, $elem, $type, $template, $extLocation, $links, $getFrom, $sourceFile );
		}
		return $links;
	}

	private static function getSiteLocalLinks($match, $elem, $type, $template, $extLocation, &$links, $getFrom, $sourceFile){

		if( $match && !preg_match('/\/\/([a-zA-Z0-9\__]+)\.([a-zA-Z0-9\__]+)\.([a-zA-Z0-9\__]+)/i', $match) && !preg_match('/\/\/([a-zA-Z0-9\__]+)\.([a-zA-Z0-9\__]+)/i', $match) && !preg_match('/'. str_replace('/', '\/', $template) .'/i', $match) ){

			$urlFile = self::getUrl($match, $elem);

			/*
			echo 'ELEM='.htmlspecialchars($elem).'<br>';
			echo 'HTML='.htmlspecialchars($match).'<br>';
			echo 'URLFILE='.$urlFile.'<br><br>';
			*/

			if( !preg_match('/google/i', $urlFile) ){

				list($new_from, $searchTag) = explode('?', $urlFile);

				$ext = pathinfo(basename($new_from), PATHINFO_EXTENSION);

				$new_from = self::getLinkLocation( $new_from, $getFrom);

				$links[] = array(
					'get_from' => $new_from, 
					'orig' => $urlFile, 
					'new' => '/' . $template . ($extLocation[$ext] ? $extLocation[$ext] : $extLocation['ext']) . $new_from . ($searchTag ? '?' . $searchTag : '') ,
					'elem' => $elem, 
					'type' => $type, 
					'data_from' => $getFrom, 
					'source-file' => $sourceFile, 
					'html' => htmlspecialchars($match)
				);
			}
		}
	}

	private static function getSiteRemoteLinks($match, $elem, $type, $template, $extLocation, &$links, $getFrom, $sourceFile){

		if( $match && (preg_match('/\/\/([a-zA-Z0-9\__]+)\.([a-zA-Z0-9\__]+)\.([a-zA-Z0-9\__]+)/i', $match) || preg_match('/\/\/([a-zA-Z0-9\__]+)\.([a-zA-Z0-9\__]+)/i', $match)) && !preg_match('/'. str_replace('/', '\/', $template) .'/i', $match) ){
				
			$urlFile = self::getUrl($match, $elem);

			/*
			echo 'ELEM='.htmlspecialchars($elem).'<br>';
			echo 'HTML='.htmlspecialchars($match).'<br>';
			echo 'URLFILE='.$urlFile.'<br><br>';
			*/

			if( !preg_match('/'.implode('|', self::$DO_NOT_DOWNLOAD).'/i', $urlFile) ){

				$new_from = $urlFile;

				if( preg_match('/([a-zA-Z0-9\__]+)\.([a-zA-Z0-9\__]+)\.([a-zA-Z0-9\__]+)/i', $new_from) || preg_match('/\/\/([a-zA-Z0-9\__]+)\.([a-zA-Z0-9\__]+)\//i', $new_from) ){

					if( substr(trim($new_from), 0, 4) != 'http' )
						$new_from = 'http:' . $new_from;

					$new_from = self::getLinkLocation( $new_from, $getFrom );

					list($new, $searchTag) = explode('?', $urlFile);
					$new = preg_replace('/https\:/s', '', $new);
					$new = preg_replace('/http\:/s', '', $new);

					$new = preg_replace('/\/\/([a-zA-Z0-9\__]+)\.([a-zA-Z0-9\__]+)\.([a-zA-Z0-9\__]+)\.([a-zA-Z0-9\__]+)\//s', '', $new);
					$new = preg_replace('/\/\/([a-zA-Z0-9\__]+)\.([a-zA-Z0-9\__]+)\.([a-zA-Z0-9\__]+)\//s', '', $new);
					$new = preg_replace('/\/\/([a-zA-Z0-9\__]+)\.([a-zA-Z0-9\__]+)\//s', '', $new);
					$new = str_replace('../', '', $new);

					$ext = pathinfo(basename($new), PATHINFO_EXTENSION);

					$links[] = array(
						'get_from' => $new_from, 
						'orig' => $urlFile, 
						'new' => '/' . $template . ($extLocation[$ext] ? $extLocation[$ext] : $extLocation['ext']) . trim($new) . ($searchTag ? '?' . $searchTag : ''), 
						'elem' => $elem, 
						'type' => $type, 
						'data_from' => $getFrom, 
						'source-file' => $sourceFile,
						'html' => htmlspecialchars($match)
					);
				}
			}
		}
	}

	private static function getUrl($match, $elem){

		preg_match('/'.$elem.'"(.*?)"/s', $match, $m);
		if( empty($m[1]) ){

			preg_match('/'.$elem.'\'(.*?)\'/s', $match, $m);
			//echo '/'.$elem.'\'(.*?)\'/s match='.htmlspecialchars($match).' <br>';
		}
		if( empty($m[1]) ){

			preg_match('/'.$elem.'\((.*?)\)/s', $match, $m);
			//echo '/'.$elem.'\((.*?)\)/s match='.htmlspecialchars($match).' <br>';
		}
		if( empty($m[1]) ){

			preg_match('/'.$elem.'(.*?)/s', $match, $m);
			//echo '/'.$elem.'(.*?)/s match='.htmlspecialchars($match).' <br>';
		}
		if( empty($m[1]) ){

			preg_match('/^'.$elem.'(.*?)$/s', $match, $m);
			//echo '/^'.$elem.'(.*?)$/s match='.htmlspecialchars($match).' <br>';
		}

		return $m[1];
	}

	private static function getLinkLocation( $from, $getFrom ){

		if( $getFrom && $from ){

			if( preg_match('/\/\/([a-zA-Z0-9\_\-_]+)\./i', $from) )
				return $from;

			$getFrom = (substr($getFrom, -1) == '/' ? substr($getFrom, 0, strlen($getFrom)-1) : $getFrom);

			$c = substr_count($from, '../');

			if( $c > 0 ){

				if( $c == 1 )
					$getFrom = dirname($getFrom);
				
				if( $c > 1 )
					$getFrom = dirname($getFrom, $c);
			}

			$from = str_replace('../', '', $from);
			$from = substr($from, 0, 1) == '/' ? substr($from, 1) : $from;

			$from = $getFrom.'/'.$from;
		}
		return $from;
	}
}

?>