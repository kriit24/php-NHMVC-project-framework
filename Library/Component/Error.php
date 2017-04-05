<?
namespace Library\Component;

class Error{

	const _PARSE_ERROR = (\Conf\Conf::_DEV_MODE ? true : false);

	private $EMAIL = array(
		'name' => "ERROR",
		'email' => "no-replay",
		'recipient' => \Conf\Conf::_EMAIL,
		'subject' => "Error ON "._URI,
		'body' => '',
		'header' => ''
	);
	private $catch;
	private $setTrace = true;
	private $trace;
	private $reportViaEmail;
	private $table;
	private $error = array();
	private $message = array();

	function __construct( $key, $message = '', $catch = false, $setTrace = true, $reportViaEmail = false ){

		$this->error[$key] = $key;
		if( $message )
			$this->message[$key] = $message;
		$this->catch = $catch;
		$this->setTrace = $setTrace;
		$this->reportViaEmail = $reportViaEmail;
	}

	static function catch(){

		return throwException::catch('ERROR');
	}

	private function construct(){

		if( $this->catch ){

			if( $this->message )
				new throwException($this->message, 'ERROR');
			else
				new throwException($this->error, 'ERROR');
		}
		else{

			$this->setHeader();
			$this->setTrace();
			$this->setBody();
			if( $this->error )
				$this->manageError();
		}
	}

	private function setHeader(){

		$this->EMAIL['header'] = "From: ". $this->EMAIL['name'] . " <" . $this->EMAIL['email'].'@'.$_SERVER['HTTP_HOST'] . ">\r\n";
	}

	private function setTrace(){

		if( $this->setTrace )
			$this->trace = \Library\Component\Trace::get();
	}

	private function setBody(){

		$body = "Error: ".implode($this->error);
		if( $this->message ){

			$body .= "\r\n\r\n";
			$body .= "message: ".implode($this->message);
		}
		if( $this->setTrace ){

			$body .= "\r\n\r\n";
			$body .= "URI="._URI;
			$body .= "\r\n\r\n";
			$body .= "TRACE:\r\n<pre>".$this->trace.'</pre>';
			$body .= "\r\n\r\n";
			$body .= "HTTP_REQUEST";
			$body .= "\r\n\r\n";
			$body .= $_GET ? '<? //GET('.date('d.m.Y H:i:s').') '.json_encode($_GET)." ?>\n" : "";
			$body .= $_POST ? '<? //POST('.date('d.m.Y H:i:s').') '.json_encode($_POST)." ?>\n" : "";
		}

		$this->EMAIL['body'] = $body;
	}

	private function showError(){

		return str_replace("\r\n", '<br/>', $this->EMAIL['body']);
	}

	private function emailError(){

		mail($this->EMAIL['recipient'], $this->EMAIL['subject'], $this->EMAIL['body'], $this->EMAIL['header']);
	}

	private function manageError(){

		if( self::_PARSE_ERROR && $this->reportViaEmail == false ){

			die($this->showError());
		}
		else{

			$this->emailError();
		}
	}

	function __destruct(){

		$this->construct();
	}
}

?>