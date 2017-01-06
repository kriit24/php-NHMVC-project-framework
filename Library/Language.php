<?
namespace Library;

class Language{

	use \Library\Component\Singleton;

	static function init(){

		if( $_GET['language'] && strlen($_GET['language']) == 3 ) 
			$_SESSION['language'] = $_GET['language'];
		define('_LANG', ($_SESSION['language'] ? $_SESSION['language'] : \Conf\Conf::_DLANG));
		define('_DLANG', \Conf\Conf::_DLANG);
	}

	function Language( $message = '' ){

		if( !$message )
			return _LANG;

		$language = new \Table\language;
		if( !$language->isConnected() || !_LANG )
			return $message;

		if( !$this->_parent ){

			$caller = debug_backtrace(false, 3);
			if( preg_match('/'._APPLICATION_PATH.'/i', $caller[0]['file']) ){

				$exp = explode(_APPLICATION_PATH, $caller[0]['file']);
				$this->_parent = substr($exp[1], 0, strrpos($exp[1], '/'));
				//$this->_parent = $caller[0]['class'];
			}
			else if( preg_match('/'._APPLICATION_PATH.'/i', $caller[1]['file']) ){

				$exp = explode(_APPLICATION_PATH, $caller[1]['file']);
				$this->_parent = substr($exp[1], 0, strrpos($exp[1], '/'));
				//$this->_parent = $caller[1]['class'];
			}
			else if( preg_match('/'._APPLICATION_PATH.'/i', $caller[2]['file']) ){

				$exp = explode(_APPLICATION_PATH, $caller[2]['file']);
				$this->_parent = substr($exp[1], 0, strrpos($exp[1], '/'));
				//$this->_parent = $caller[2]['class'];
			}
		}

		$model = $this->_parent;
		$pos = strrpos($model, '\\');
		if( $pos )
			$model = substr($model, 0, $pos);
		$model = str_replace('\\', '/', $model);
		return $this->getLanguage($message, $model);
	}

	private function getLanguage($message, $model){

		$redis = new \Library\Redis;
		if( !$redis->isConnected() )
			return $this->getLanguageFromDb($message, $model);

		$lang = $redis->get('language');
		$langKey = md5($model.'_'.$message.'_'._LANG);

		if( !$lang ){

			$message = $this->getLanguageFromDb($message, $model);
			$redis->set('language', array($langKey => $message));
		}
		else{

			if( !in_array($langKey, array_keys($lang)) ){

				$message = $this->getLanguageFromDb($message, $model);
				$redis->append('language', array($langKey => $message));
			}
			else
				$message = $lang[$langKey];
		}
		return $message;
	}

	private function getLanguageFromDb($message, $model){

		$language = new \Table\language;

		$row = $language->Select()
			->column('SQL_CACHE *')
			->where("name = '".$message."' AND language = '"._LANG."' AND model = '".$model."' ")
			->fetch();
		if( $language->Numrows() == 0 ){

			$language->Insert(array('name' => $message, 'value' => $message, 'language' => _LANG, 'model' => $model));
			return $message;
		}
		return $row['value'];
	}
}

?>