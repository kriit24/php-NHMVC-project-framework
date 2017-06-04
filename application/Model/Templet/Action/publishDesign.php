<?
namespace Model\Templet\Action;

class publishDesign extends \Library{

	private static $htmlXml = array();
	var $dataIndex = 1;

	public static function init(){

		$dir = _DIR .'/' . _APPLICATION_PATH .'/Template/'. \Conf\Conf::_TEMPLATE .'/public/design';

		if( !is_file($dir.'/header-pre.php') )
			return;

		$content = file_get_Contents($dir.'/header-pre.php');

		preg_match('/(.*?)<\/head>/s', $content, $match);
		$header_php = $match[0];
		if( !preg_match('/\/inc\/header\.php/i', $header_php) ){

			$header_php = preg_replace('/<\/head>/s', "\n" . '<? require __DIR__ .\'/inc/header.php\'; ?></head>', $header_php);
			$header_php = preg_replace('/<\/ head>/s', "\n" . '<? require __DIR__ .\'/inc/header.php\'; ?></head>', $header_php);
			$header_php = preg_replace('/< \/head>/s', "\n" . '<? require __DIR__ .\'/inc/header.php\'; ?></head>', $header_php);
			$header_php = self::setHeader($header_php);
		}

		$content = file_get_Contents($dir.'/body-pre.php');

		preg_match('/<body(.*?)<\/body>/s', $content, $match);
		$body_php = $match[0];
		if( !preg_match('/\/inc\/footer\.php/i', $body_php) ){

			$body_php = preg_replace('/<\/body>/s', "\n" . '<? require __DIR__ .\'/inc/footer.php\'; ?></body>', $body_php);
			$body_php = preg_replace('/<\/ body>/s', "\n" . '<? require __DIR__ .\'/inc/footer.php\'; ?></body>', $body_php);
			$body_php = preg_replace('/< \/body>/s', "\n" . '<? require __DIR__ .\'/inc/footer.php\'; ?></body>', $body_php);
		}
		if( !preg_match('/\/inc\/body\.php/i', $body_php) ){

			$body_php = self::setContent( $body_php );
			$body_php = preg_replace('/<body(.*?)>/s', "\n" . '<body\\1><? require __DIR__ .\'/inc/body.php\'; ?>', $body_php);
		}

		$content = file_get_Contents($dir.'/footer-pre.php');

		preg_match('/\<\/body\>(.*?)$/s', $content, $match);
		$footer_php = $match[1];

		//die(pre(htmlspecialchars($header_php)));
		//die(pre(htmlspecialchars($body_php)));
		//die(pre(htmlspecialchars($footer_php)));

		file_put_contents($dir . '/header.php', $header_php);
		file_put_contents($dir . '/body.php', $body_php);
		file_put_contents($dir . '/footer.php', $footer_php);

		unlink($dir.'/header-pre.php');
		unlink($dir.'/body-pre.php');
		unlink($dir.'/footer-pre.php');

		$self = new self();
		$self->createTags();

		//die(\Library\Http::redirect('?action=updatedesign'));
	}

	private static function setContent( $content ){

		//$content = preg_replace('/<div(.*?)<\/div>/s', '', $content);
		//$content = preg_replace('/<section(.*?)<\/section>/s', '', $content);

		/*$tableName = new \Table\style;
		$rows = $tableName->Select();

		foreach($rows as $row){

			$html = $row['html'];
			$html = str_replace(array('{', '[', ']', '}'), array('\{', '\[', '\]', '\}'), $html);

			preg_match('/<(.*?)>/s', $html, $matches);
			preg_match('/<([a-zA-Z_]+)>/s', $matches[0], $match);
			if( empty($match) )
				preg_match('/<([a-zA-Z_]+)(.*?)>/s', $matches[0], $match);

			$tagStart = $match[0];
			$tagEnd = '<\/' . $match[1] . '>';

			$content = preg_replace( '/'.$tagStart.'(.*?)'.$tagEnd.'/s', '', $content);
		}*/

		//echo '<b>CONTENT</b><br>';
		//die(htmlspecialchars($content));
		return $content;
	}

	private static function setHeader( $content ){

		preg_match('/<title>(.*?)<\/title>/s', $content, $match);
		$content = preg_replace('/<title>(.*?)<\/title>/s', '', $content);
		$title = $match[1];

		preg_match('/<meta(.*?)name="description"(.*?)content="(.*?)">/s', $content, $match);
		$content = preg_replace('/<meta(.*?)name="description"(.*?)content="(.*?)">/s', '', $content);
		$description = $match[3];

		preg_match('/<meta(.*?)name="keywords"(.*?)content="(.*?)">/s', $content, $match);
		$content = preg_replace('/<meta(.*?)name="keywords"(.*?)content="(.*?)">/s', '', $content);
		$keywords = $match[3];

		$content = preg_replace('/<meta(.*?)name="author"(.*?)content="(.*?)">/s', '<meta\\1name="author"\\2content="www.projectpartner.ee"/>', $content);
		$content = preg_replace('/<meta(.*?)name="author"(.*?)content="(.*?)"\/>/s', '<meta\\1name="author"\\2content="www.projectpartner.ee"/>', $content);

		$addToHeader = array(
			'cache-control' => '<meta http-equiv="cache-control" content="no-cache"/>',
			'pragma' => '<meta http-equiv="pragma" content="no-cache"/>',
			'X-UA-Compatible' => '<meta http-equiv="X-UA-Compatible" content="IE=edge">',
			'SKYPE_TOOLBAR' => '<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE"/>',
			'viewport' => '<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />',
			'author' => '<meta name="author" content="www.projectpartner.ee">',
			'robots' => '<meta name="robots" content="index, follow"/>',
		);

		foreach($addToHeader as $tag => $html){

			if( !preg_match('/'.$tag.'/i', $content) )
				$content = str_replace('<head>', '<head>' . "\n" . $html, $content);
		}

		$tableName = new \Table\style;
		$rows = $tableName->Select();
		foreach($rows as $row){

			if( !$row['title'] )
				$row['title'] = $title;

			if( !$row['keywords'] )
				$row['keywords'] = $keywords;

			if( !$row['description'] )
				$row['description'] = $description;

			$row['preview']['title'] = $title;
			$row['preview']['keywords'] = $keywords;
			$row['preview']['description'] = $description;

			\Table\content::singleton()->Update( $row, array('id' => $row['id']) );
		}

		//die(htmlspecialchars($content));
		return $content;
	}

	private function createTags(){

		$data = \Library\Component\Cache::get('template');
		if( !empty($data) )
			$this->dataIndex = count($data);

		if( is_dir(_DIR .'/'. _APPLICATION_PATH .'/Template/' . \Conf\Conf::_TEMPLATE .'/public/design') ){

			$files = $this->scanfile( _DIR .'/'. _APPLICATION_PATH .'/Template/' . \Conf\Conf::_TEMPLATE .'/public/design' );
			$this->readFiles( $files );
		}

		if( is_dir(_DIR .'/'. _APPLICATION_PATH .'/Template/' . \Conf\Conf::_TEMPLATE .'/public/ext') ){

			$files = $this->scanfile( _DIR .'/'. _APPLICATION_PATH .'/Template/' . \Conf\Conf::_TEMPLATE .'/public/ext', true );
			$this->readFiles( $files );
		}
		if( is_dir(_DIR .'/'. _APPLICATION_PATH .'/Template/' . \Conf\Conf::_TEMPLATE .'/public/css') ){

			$files = $this->scanfile( _DIR .'/'. _APPLICATION_PATH .'/Template/' . \Conf\Conf::_TEMPLATE .'/public/css' );
			$this->readFiles( $files, array('flag-icon'), true );
		}
		if( is_Dir(_DIR .'/'. _APPLICATION_PATH .'/Template/' . \Conf\Conf::_TEMPLATE .'/public/js') ){

			$files = $this->scanfile( _DIR .'/'. _APPLICATION_PATH .'/Template/' . \Conf\Conf::_TEMPLATE .'/public/js' );
			$this->readFiles( $files, array(), true );
		}

		$this->getFromContent();
	}

	private function getFromContent(){

		$getFilesByExtension = array('png', 'jpg', 'jpeg', 'gif');

		$rows = \Table\content::singleton()->Select();
		$rows_2 = \Table\style::singleton()->Select();
		$menu = array();
		$data = \Library\Component\Cache::get('template');
		if( empty($data) )
			$data = array();

		foreach($rows as $row){

			$menu[ $row['related_id'] ] = $row['menu'];

			foreach( array('content') as $arrayKey ){

				preg_match_all('/url\((.*?)\)/s', $row[ $arrayKey ], $matches);
				if( !empty($matches[1]) ){

					$row[ $arrayKey ] = $this->getTags($matches[1], $data, $row[ $arrayKey ]);
					\Table\style::singleton()->Update( $row, array('id' => $row['id']) );
				}

				preg_match_all('/src\="(.*?)"/s', $row[ $arrayKey ], $matches);
				if( !empty($matches[1]) ){

					$row[ $arrayKey ] = $this->getTags($matches[1], $data, $row[ $arrayKey ]);
					\Table\style::singleton()->Update( $row, array('id' => $row['id']) );
				}
			}
		}

		foreach($rows_2 as $row){

			foreach( array('html', 'style', 'script') as $arrayKey ){

				preg_match_all('/url\((.*?)\)/s', $row[ $arrayKey ], $matches);
				if( !empty($matches[1]) ){

					$row[ $arrayKey ] = $this->getTags($matches[1], $data, $row[ $arrayKey ]);
					\Table\style::singleton()->Update( $row, array('id' => $row['id']) );
				}

				preg_match_all('/src\="(.*?)"/s', $row[ $arrayKey ], $matches);
				if( !empty($matches[1]) ){

					$row[ $arrayKey ] = $this->getTags($matches[1], $data, $row[ $arrayKey ]);
					\Table\style::singleton()->Update( $row, array('id' => $row['id']) );
				}
			}
		}

		if( !empty($data) )
			\Library\Component\Cache::set('template', $data);
	}

	private function readFiles( $files, $exclude = array(), $getFile = false ){

		$data = \Library\Component\Cache::get('template');
		if( empty($data) )
			$data = array();

		$getFilesByExtension = array('png', 'jpg', 'jpeg', 'gif');

		foreach($files as $file){

			$fileContent = file_get_contents($file);

			if( in_array(pathinfo(basename($file), PATHINFO_FILENAME), $exclude) )
				continue;

			preg_match_all('/url\((.*?)\)/s', $fileContent, $matches);
			if( !empty($matches[1]) ){

				$c = $this->getTags($matches[1], $data, $fileContent);
				file_put_contents($file, $c);
			}

			preg_match_all('/src\="(.*?)"/s', $fileContent, $matches);
			if( !empty($matches[1]) ){

				$c = $this->getTags($matches[1], $data, $fileContent);
				file_put_contents($file, $c);
			}
		}

		if( !empty($data) )
			\Library\Component\Cache::set('template', $data);
	}

	private function getTags($matches, &$data, $content){

		$include = array('png', 'jpg', 'jpeg', 'gif');

		foreach($matches as $value){

			if( !preg_match('/\/\/([a-zA-Z0-9\__]+)\.([a-zA-Z0-9\__]+)/i', $value) && is_file(_DIR . $value) ){

				if( in_array(pathinfo(basename(_DIR . $value), PATHINFO_EXTENSION), $include) ){

					$data['file-'.$this->dataIndex] = trim($value);
					$content = preg_replace('/'.str_REplace( array('/'), array('\/'), $value).'/', '{[file-'.$this->dataIndex.']}', $content, 1);

					$this->dataIndex ++;
				}
			}
		}
		return $content;
	}
}

?>