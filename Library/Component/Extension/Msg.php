<?
namespace Library\Component\Extension;

trait Msg{

	public function error($message, $replacement = array()){

		$message = $this->Language($message);
		$message = $this->replace($message, $replacement);
		new \Library\Component\Error($message, '', true);
	}

	public function message($message, $replacement = array()){

		$message = $this->Language($message);
		$message = $this->replace($message, $replacement);
		new \Library\Component\Message($message);
	}

	public function getError( $key = '' ){

		if( $key )
			return \Library\Component\Error::catch()[$key];
		else
			return \Library\Component\Error::catch();
	}

	public function getMessage( $key = '' ){

		if( $key )
			return \Library\Component\Message::catch()[$key];
		else
			return \Library\Component\Message::catch();
	}
}
?>