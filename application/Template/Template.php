<?
namespace Template;

class Template{

	const D_PUBLIC = 'public';
	const D_ADMIN = 'admin';
	const DEBUG = (_DEBUG == 'template' ? true : false);
	private $design;
	private $template = '';
	public static $Includes = array();
	public $header = array('CSS' => array(), 'JS' => array());

	public function construct(){

		list($classMethod, $template) = \Library\Structur\Loader::getLoader();
		$this->setObject( $classMethod );
		$this->design( _APPLICATION_ENV, ($template ?: \Conf\Conf::_TEMPLATE) );
		$this->load();
		
		//echo 'TEMPLATE<br>';
		//pre( $classMethod );
	}

	private function design( $env, $template ){

		if( $env == 'public' )
			$this->design = self::D_PUBLIC;
		if( $env == 'admin' )
			$this->design = self::D_ADMIN;
		$this->template = $template;
	}

	private function setObject( $classMethod ){

		if( empty($classMethod) )
			return false;

		$this->classMethod = $classMethod;
	}

	private function load(){

		if( empty($this->classMethod) )
			return false;

		if( $this->classMethod['none'] ){

			return $this->content('none');
		}

		if( !is_dir(__DIR__.'/'.$this->template) )
			die('Template dont exists: '.__DIR__.'/'.$this->template);

		//$this->header();

		require_once __DIR__.'/'.$this->template.'/'.$this->design.'/design/header.php';
		require_once __DIR__.'/'.$this->template.'/'.$this->design.'/design/body.php';
		require_once __DIR__.'/'.$this->template.'/'.$this->design.'/design/footer.php';
	}

	public function header(){

		$Includes = \Library\Component\Register::getRegister('INCLUDES');
		self::$Includes = $Includes;
		\Library\Component\Register::cleanRegister('INCLUDES');

		$this->header = array(
			'CSS' => array(),
			'JS' => array(),
		);

		if( $Includes ){

			foreach($Includes as $href){

				if( substr(basename($href), -4) == '.css' )
					$this->header['CSS'][] = '<link rel="stylesheet" href="'.$this->toUrl($href).'?ver='.strtotime('now').'" type="text/css"/>';
				if( substr(basename($href), -3) == '.js' )
					$this->header['JS'][] = '<script src="'.$this->toUrl($href).'?ver='.strtotime('now').'" type="text/javascript"></script>';
			}
		}
	}

	public function content( $position ){

		if( self::DEBUG == 'template' )
			echo '[POSITION='.$position.']<br>';

		if( is_Array($this->classMethod[$position]) ){
			
			foreach( $this->classMethod[$position] as $val){

				if( $val['method'] && $val['classname'] ){

					//pre($val);
					$reflectionMethod = new \ReflectionMethod($val['class'], $val['method']);
					if( !$reflectionMethod->isPublic() )
						$reflectionMethod->setAccessible(true);
					$reflectionMethod->invoke($val['class'], null);
				}
			}
		}
	}

	public function footer(){

		$self = new self();
		$self::$Includes = self::$Includes;
		$self->includes( $this->template );
	}

	public static function includes( $template = '' ){

		$Includes = \Library\Component\Register::getRegister('INCLUDES');
		if( $Includes ){

			$cssString = null;
			$jsString = null;

			foreach($Includes as $href){

				if( in_array($href, self::$Includes) )
					continue;

				array_push(self::$Includes, $href);

				$templateFolder = basename(__DIR__);
				if( substr($href, 0, (strlen($templateFolder) + 2) ) == '/'.$templateFolder.'/' )
					$href = __DIR__ . str_replace($templateFolder, $template, $href);

				if( substr(basename($href), -4) == '.css' )
					$cssString .= file_Get_contents( $href ) . "\n";
				if( substr(basename($href), -3) == '.js' )
					$jsString .= file_Get_contents( $href ) . "\n";
			}
			if( $cssString )
				echo '<style type="text/css">'.$cssString.'</style>';

			if( $jsString )
				echo '<script type="text/javascript">'.$jsString.'</script>';
		}
	}
}

?>