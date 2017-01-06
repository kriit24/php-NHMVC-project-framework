<?PHP
namespace Package\Extension;

/**
* sends email
*/
trait Email{

	private function setOption( $name, $value ){

		$this->option[$name] = $value;
	}

	private function getOption( $name ){

		return $this->option[$name];
	}

	private function _setTemplate( $name ){

		$template = Table('template');
		$row = $template->Select()
			->where("name = '".$name."' AND language = '"._LANG."' AND type = 'EMAIL' ")
			->fetchrow();

		if( $template->Numrows() > 0 ){

			$this->setFromName( $row['from_name'] );
			$this->setFromEmail( $row['from_email'] );
			$this->setSubject( $row['subject'] );
			$this->setContent( $row['content'] );
		}
	}

	private function _sendEmail( $type ){

		if( $type == 'phpmailer' )
			return $this->_phpMailer();
		else
			return $this->_regularMailer();
	}

	/**
	* same as sendStoredRegularEmail but dont uses saved template
	*/
	private function _regularMailer(){

		$fromName = $this->getOption('from_name'); //senders fromName
		$fromEmail = $this->getOption('from_email'); //senders e-mail adress
		$header = "From: ". $fromName . " <" . $fromEmail . ">\r\n"; //optional headerfields

		$isSent = true;
		mail(implode(',', $this->getOption('to_email')), $this->getOption('subject'), $this->getOption('content'), $header) or ($isSent = false); //mail command :)
		$this->isSent = $isSent;
		return $this;
	}

	private function _phpMailer(){

		$mail = new \PHPMailer();
		$mail->IsSMTP();
    	$mail->IsHTML(true);
    	$mail->CharSet = 'UTF-8';

        $mail->Host = "localhost";
        $mail->From = $this->getOption('from_email');
        $mail->FromName = $this->getOption('from_name');

		if( is_Array($this->getOption('to_email')) ){

			foreach($this->getOption('to_email') as $k => $v){

				$toEmail = $v;
				$toName = $this->getOption('to_name')[$k];
				$mail->AddAddress($toEmail, $toName);
			}
		}
		else
			$mail->AddAddress($this->getOption('to_email'), $this->getOption('to_name'));

        
		if($this->getOption('attachment')){

			foreach($this->getOption('attachment') as $k => $v){

				$alias = '';
				$file = $v;
				if( !is_numeric($k) )
					$alias = $k;

				if( $alias )
					$mail->AddAttachment($file, $alias);
				else
					$mail->AddAttachment($file);
			}
		}

        $mail->Subject = "=?utf-8?b?".base64_encode($this->getOption('subject'))."?=";
        $mail->Body = $this->getOption('content');
        $mail->AltBody = $this->getOption('plain_content');

		$isSent = false;
		if($mail->Send()) $isSent = true;
		$mail->isSent = $isSent;
		return $mail;
	}
}
?>