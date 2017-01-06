<?
namespace Model\Translate\Action;

abstract class truncate{

	public static function init(){

		\Table\language::singleton()->query("TRUNCATE TABLE language");
		$redis = new \Library\Redis;
		if( $redis->isConnected() )
			$redis->delete('language');
		die(\Library\Http::redirect( \Library\Url::singleton()->url(array('model' => 'Translate')) ));
	}
}

?>