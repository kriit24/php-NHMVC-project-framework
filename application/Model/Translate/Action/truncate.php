<?
namespace Model\Translate\Action;

abstract class truncate{

	public static function init(){

		\Table\translate::singleton()->query("TRUNCATE TABLE translate");
		$redis = new \Library\Redis;
		if( $redis->isConnected() )
			$redis->delete('translate');
		die(\Library\Http::redirect( \Library\Url::singleton()->url(array('model' => 'Translate')) ));
	}
}

?>