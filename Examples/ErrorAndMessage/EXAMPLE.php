<?
//ERROR AND MESSAGE uses throwException

//Library method "error"
$this->error('error message {key_to_replace}', array('key_to_replace' => 'value'));
$this->error('second error');

echo $this->getError();

//class usage
new \Library\Component\Error('message', '', true);//set true if u want catch later
echo \Library\Component\Error::catch();

new \Library\Component\Error('message');//if u want echo

//MESSAGE

//Library method "message"
$this->message('message {key_to_replace}', array('key_to_replace' => 'value'));
$this->message('second message');

echo $this->getMessage();

//class usage
new \Library\Component\Message('message', '', true);//set true if u want catch later
echo \Library\Component\Message::catch();

new \Library\Component\Message('message');//if u want echo

//ALSO U CAN USE TROW-CATCH METHOD
new \Library\Component\throwException('message', 'custom_type');
echo \Library\Component\throwException::catch('custom_type');//catch all messages by custom_type

?>