<?PHP
namespace Package;

class Email{

	use Extension\Email;

	protected $option = array();

	function __construct(){

		require_once __DIR__.'/Phpmailer/class.phpmailer.php';
	}

	/*
	$value = 'to name';
	$value = array('name1', 'name2');
	*/
	function setToName( $value ){

		$value = !is_array($value) ? array($value) : $value;
		$this->setOption('to_name', $value);
	}

	/*
	$value = 'to email';
	$value = array('email1', 'email2');
	*/
	function setToEmail( $value ){

		$value = !is_array($value) ? array($value) : $value;
		$this->setOption('to_email', $value);
	}

	function setFromName( $value ){

		$this->setOption('from_name', $value);
	}

	function setFromEmail( $value ){

		$this->setOption('from_email', $value);
	}

	function setSubject( $value ){

		$this->setOption('subject', $value);
	}

	/*
	if u want get template from database
	*/
	function setTemplate( $value ){

		$this->_setTemplate($value);
	}

	function setContent( $value ){

		$this->setOption('content', $value);
		$this->setPlainContent($value);
	}

	function setPlainContent( $value ){

		$this->setOption('plain_content', trim(str_replace('    ', '', strip_tags(str_replace('<br>', "\r\n", $value))))); //plain text);
	}

	//array('attach1', 'attach2');
	function setAttachment( $value ){

		if( !is_array($value) )
			$this->setOption('attachment', array($value));
		else
			$this->setOption('attachment', $value);
	}

	/**
	* $type = phpmailer, regularmailer
	*/
	function sendEmail( $type = 'phpmailer' ){

		return $this->_sendEmail( $type );
	}

	function __destruct(){

		$this->option = array();
	}
}
?>