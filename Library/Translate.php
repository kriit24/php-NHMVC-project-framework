<?
namespace Library;

class Translate{

	use \Library\Component\Singleton;

	public $_parent = '';

	static function init(){

		$redis = new \Library\Redis;
		$sql = new \Library\Sql;
		if( $redis->isConnected() ){

			$lang = $redis->get('translate');
			\Library\Component\Register::register('TRANSLATE', $lang, \Library\Component\Register::IS_ARRAY);
		}
		else if( $sql->isConnected() ){

			$self = new self();
			$lang = $self->getAllTranslateFromDb();
			\Library\Component\Register::register('TRANSLATE', $lang, \Library\Component\Register::IS_ARRAY);
		}
	}

	function Translate( $message = '' ){

		if( !$message )
			return _LANG;

		$translate = new \Table\translate;
		if( !$translate->isConnected() || !_LANG )
			return $message;

		$model = $this->_parent;
		$pos = strrpos($model, '\\');
		if( $pos )
			$model = substr($model, 0, $pos);
		$model = str_replace('\\', '/', $model);
		return $this->getTranslate($message, $model);
	}

	private function getTranslate($message, $model){

		$lang = \Library\Component\Register::getRegister('TRANSLATE');
		$langKey = md5($model.'_'.$message.'_'._LANG);

		if( !$lang ){

			$message = $this->getTranslateFromDb($message, $model);
			$redis = new \Library\Redis;
			if( $redis->isConnected() )
				$redis->set('translate', array($langKey => $message));
		}
		else{

			if( !in_array($langKey, array_keys($lang)) ){

				$message = $this->getTranslateFromDb($message, $model);
				$redis = new \Library\Redis;
				if( $redis->isConnected() )
					$redis->append('translate', array($langKey => $message));
			}
			else
				$message = $lang[$langKey];
		}
		return $message;
	}

	private function getTranslateFromDb($message, $model){

		$translate = new \Table\translate;

		$row = $translate->Select()
			->column('SQL_CACHE *')
			->where("name = '".$message."' AND language = '"._LANG."' AND model = '".$model."' ")
			->fetch();
		if( $translate->Numrows() == 0 ){

			$translate->Insert(array('name' => $message, 'value' => $message, 'language' => _LANG, 'model' => $model));
			return $message;
		}
		return $row['value'];
	}

	private function getAllTranslateFromDb(){

		$translate = new \Table\translate;
		$ret = array();

		$translate->Select()
			->column('SQL_CACHE *')
			->where("language = '"._LANG."' ");
		if( $translate->Numrows() > 0 ){

			while($row = $translate->fetch()){

				$langKey = md5($row['model'].'_'.$row['name'].'_'._LANG);
				$ret[$langKey] = $row['value'];
			}
		}
		return $ret;
	}
}

?>