<?
namespace Library\Component;

class Message{

	function __construct( $message ){

		new throwException(array($message), 'MESSAGE');
	}

	static function catch(){

		return throwException::catch('MESSAGE');
	}
}

?>