<?
namespace Library;

class Script{

	use \Library\Component\Singleton;

	function script( $scriptArray ){

		echo '<script type="text/javascript">';

		if( !is_array($scriptArray) ){

			$this->parseScript($scriptArray);
		}
		else{

			foreach($scriptArray as $k => $v){

				$varName = !is_numeric($k) ? $k : false;
				if( $varName ){

					$this->parseScript('var '.$varName.' = "'.$v.'"');
				}
				else{

					$this->parseScript($v);
				}
			}
		}

		echo '</script>';
	}

	private function parseScript($script){

		if( substr($script, -1) == ';' )
			echo $script;
		else
			echo $script.';';
	}
}
?>