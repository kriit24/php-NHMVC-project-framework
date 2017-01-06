<?
namespace Library\Component;

class throwException{

	function __construct($message, $type){

		Register::setRegister($type, $message);
	}

	static function throw($type){

		$message = Register::getRegister($type);
		if( $message )
			throw new \Exception( json_encode($message) );
	}

	static function catch($type){

		try{

			self::throw($type);
		}
		catch (\Exception $e) {

		   return json_decode( $e->getMessage(), true );
		}
		return false;
	}
}

?>