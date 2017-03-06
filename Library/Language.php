<?
namespace Library;

class Language{

	use \Library\Component\Singleton;

	public $_parent = '';

	static function init(){

		if( $_GET['language'] && strlen($_GET['language']) == 3 ) 
			$_SESSION['language'] = $_GET['language'];
		define('_LANG', ($_SESSION['language'] ? $_SESSION['language'] : \Conf\Conf::_DLANG));
		define('_DLANG', \Conf\Conf::_DLANG);

		$redis = new \Library\Redis;
		if( $redis->isConnected() ){

			$lang = $redis->get('language');
			\Library\Component\Register::register('LANGUAGE', $lang, \Library\Component\Register::IS_ARRAY);
		}
		else{

			$lang = $this->getAllLanguageFromDb();
			\Library\Component\Register::register('LANGUAGE', $lang, \Library\Component\Register::IS_ARRAY);
		}
	}

	function Language( $message = '' ){

		if( !$message )
			return _LANG;

		$language = new \Table\language;
		if( !$language->isConnected() || !_LANG )
			return $message;

		$model = $this->_parent;
		$pos = strrpos($model, '\\');
		if( $pos )
			$model = substr($model, 0, $pos);
		$model = str_replace('\\', '/', $model);
		return $this->getLanguage($message, $model);
	}

	private function getLanguage($message, $model){

		$lang = \Library\Component\Register::getRegister('LANGUAGE');
		$langKey = md5($model.'_'.$message.'_'._LANG);

		if( !$lang ){

			$message = $this->getLanguageFromDb($message, $model);
			$redis = new \Library\Redis;
			if( $redis->isConnected() )
				$redis->set('language', array($langKey => $message));
		}
		else{

			if( !in_array($langKey, array_keys($lang)) ){

				$message = $this->getLanguageFromDb($message, $model);
				$redis = new \Library\Redis;
				if( $redis->isConnected() )
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

	private function getAllLanguageFromDb(){

		$language = new \Table\language;
		$ret = array();

		$language->Select()
			->column('SQL_CACHE *')
			->where("language = '"._LANG."' ");
		if( $language->Numrows() > 0 ){

			while($row = $language->fetch()){

				$langKey = md5($row['model'].'_'.$row['name'].'_'._LANG);
				$ret[$langKey] = $row['value'];
			}
		}
		return $ret;
	}
}

?>