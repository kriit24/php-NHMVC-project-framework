<?
namespace Library\Component\Extension;

trait View{

	private function getViewFile($_dir, $view){

		if(preg_match('/\\'.DIRECTORY_SEPARATOR.'/i', $view)){

			$path = dirname($view);
			$view = basename($view);

			if(file_exists($_dir.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.$path.DIRECTORY_SEPARATOR.$view.'.php'))
				$path = $_dir.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.$path;
		}
		else{

			$path = $_dir.DIRECTORY_SEPARATOR.'view';
		}
		return $path.DIRECTORY_SEPARATOR.$view.'.php';
	}

	/**
	* require pageName
	* @param String $page - pageName
	* @param Array $variables = array() - it will pass thrue variables as $variableName
	*/
	public function view($view, $return = false){

		$reflection = \Library::reflection(get_class($this));
		
		$file = $this->getViewFile(
			dirname($reflection->getFileName()),
			$view
		);
		if( !is_file($file) && $reflection->getParentClass() ){

			$file = $this->getViewFile(
				dirname($reflection->getParentClass()->getFileName()),
				$view
			);
		}

		if( file_exists($file) ){

			if( self::DEBUG ) echo $file.'<br>';
			if( $return ){

				ob_start();
				require $file;
				$content = ob_get_contents();
				ob_end_clean();
				return $content;
			}
			require $file;
		}
		else{

			new \Library\Component\Error('<b>File does not exists:'.$file.'</b>');
		}
	}
}
?>