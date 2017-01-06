<?
namespace Command\Install\Action;

abstract class isInstalled{

	public static function get(){

		if( is_Array(\Conf\Conf::_DB_CONN) )
			return true;
		return false;
	}
}
?>