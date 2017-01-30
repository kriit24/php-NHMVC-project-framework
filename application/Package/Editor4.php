<?PHP
namespace Package;

/**
* fckEditor class
* config.toolbar_Full =
[
	{ name: 'document', items : [ 'Source','-','Save','NewPage','DocProps','Preview','Print','-','Templates' ] },
	{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
	{ name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
	{ name: 'forms', items : [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 
        'HiddenField' ] },
	'/',
	{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
	{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv',
	'-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
	{ name: 'links', items : [ 'Link','Unlink','Anchor' ] },
	{ name: 'insert', items : [ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe' ] },
	'/',
	{ name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
	{ name: 'colors', items : [ 'TextColor','BGColor' ] },
	{ name: 'tools', items : [ 'Maximize', 'ShowBlocks','-','About' ] }
];
 
config.toolbar_Basic =
[
	['Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink','-','About']
];
*/
class Editor4{

	use \Library\Component\Singleton;

	const TOOLBAR_SIMPLE = array(
		array( 'Source', '-', 'Bold', 'Italic', 'Underline', 'Strike' ),
		array( 'NumberedList','BulletedList', '-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ),
		array( 'Image' ),
		array( 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' )
	);

	const TOOLBAR_NORMAL = array(
		array( 'Source', '-', 'Bold', 'Italic', 'Underline', 'Strike' ),
		array( 'NumberedList','BulletedList', '-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ),
		array( 'Styles','Format','Font','FontSize' ),
		array( 'TextColor','BGColor' ),
		array( 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' )
	);

	function __construct(){

		require_once __DIR__.'/Ckeditor4/ckeditor.php';
	}

	/**
	* calling ckeditor texteditor
	* @param Int $width=950
	* @param Int $height=425
	* @param String $name = 'fckeditor'
	* @param String $content
	* @param Array $config=array() - ckeditor config
	* @return ckeditor html
	*/
	function editor($content, $width=950, $height=425, $name = 'fckeditor', $setConfig = array(), $toolbar = array()){

		$ret = '';
		$ret = '<script type="text/javascript">';
		$ret .= '//<![CDATA['."\n";
		$ret .= 'if(window.CKEDITOR){';
		$ret .= 'if (CKEDITOR.instances["'.$name.'"]) { CKEDITOR.remove(CKEDITOR.instances["'.$name.'"]);}';
		$ret .= '}';
		$ret .= "\n".'//]]>';
		$ret .= '</script>';
		$ret .= '<style type="text/css">';
		$ret .= '.cke_browser_webkit table tr:hover, .cke_browser_webkit table tr:hover th, .cke_browser_webkit table tr:hover td{';
		$ret .= 'background:none !important;';
		$ret .= '}';
		$ret .= '.cke_browser_webkit table td, .cke_browser_webkit table th{';
		$ret .= 'border:0px;';
		$ret .= '}';
		$ret .= '</style>';

		//$Uri = ( substr($_SERVER['HTTP_REFERER'], 0, stripos($_SERVER['HTTP_REFERER'], '/')) ) .'http//'. $_SERVER['HTTP_HOST'];
		$Uri = '';
		$Uri .= str_replace(_DIR, '', __DIR__);
		$Uri .= '/Ckeditor4/';

		$CKEditor = new \CKEditor4();
		$CKEditor->returnOutput = true;
		$CKEditor->basePath = $Uri;
		$CKEditor->config['width'] = $width;
		$CKEditor->config['height'] = $height;
		$CKEditor->textareaAttributes = array("cols" => 80, "rows" => ($height / 10 ) );
		$config['autoParagraph'] = false;
		$config['resize_enabled'] = false;
		
		if( !empty($setConfig) )
			$config = array_merge($config, $setConfig);
		
		if( !empty($toolbar) )
			$config['toolbar'] = $toolbar;

		$ret .= $CKEditor->editor($name, $content, $config);
		return $ret;
	}
}

?>