<?
namespace Command\Command;

abstract class Params{

	public static function get(){

		$param = $_GET['param'];
		$commands = array();
		if( !preg_match('/-/i', $param) )
			return $param;

		foreach(explode('-', $param) as $v){

			if( $v ){

				$commands['-'.substr($v, 0,1)] = trim(substr($v, 1));
			}
		}
		return $commands;
	}
}
?>