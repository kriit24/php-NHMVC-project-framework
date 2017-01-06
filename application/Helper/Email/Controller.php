<?
namespace Helper\Email;

class Controller{

	public function email(){

		$email = new \Library\Email;
		$email->setToName('');
		$email->setToEmail('');
		$email->setFromName('');
		$email->setFromEmail('');
		$email->setSubject('');
		$email->setContent('');
		//$email->setAttachment(array('output.txt' => _DIR.'/tmp/error-output.txt', _DIR.'/tmp/some.txt'));
		$email->sendEmail();
	}
}

?>