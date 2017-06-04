<?
namespace Model\Templet\Action;

abstract class addContent{

	public static function init(){

		$dir = _DIR . '/tmp/data/uploaded/content';
		if( !is_dir($dir) )
			mkdir($dir, 0755, true);
		$file = $_POST['menu'].'.php';

		$HTTP_POST = \Library\Http::POST();
		$content = base64_decode($HTTP_POST['base64_content']);
		$content = str_replace(' class="layout-copy-border"', '', $content);
		$content = str_replace('class="layout-copy-border"', '', $content);
		$content = str_replace(' layout-copy-border', '', $content);
		$content = str_replace('layout-copy-border', '', $content);

		//die(htmlspecialchars($content));

		if( is_file($dir .'/'. $file) ){

			$rows = \Table\content::singleton()->Select( array('menu' => $_POST['menu'], 'language' => _DLANG) );
			$row = $rows[0];
			if( empty($row) ){

				$insertId = \Model\Content\Action\addContent::init( false );
				\Model\Content\Action\updateStyle::init( array('related_id' => $insertId, 'html' => $content, 'preview' => array('html' => $content)) );

				file_put_contents($dir .'/'. $file, $content);
			}
			else{

				\Model\Content\Action\updateStyle::init( array('related_id' => $row['related_id'], 'html' => $content, 'preview' => array('html' => $content)) );
			}
		}
		else{

			$insertId = \Model\Content\Action\addContent::init( false );
			\Model\Content\Action\updateStyle::init( array('related_id' => $insertId, 'html' => $content, 'preview' => array('html' => $content)) );

			file_put_contents($dir .'/'. $file, $content);
		}
	}
}

?>