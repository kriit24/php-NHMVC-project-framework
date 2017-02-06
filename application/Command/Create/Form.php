<?
namespace Command\Create;

class Form extends \Library{

	//const FOLDER = array('Api', 'Helper', 'Model', /*'Command',*/ 'Cron');
	const CREATE = array(//allways use ucfirst names
		'' => '',
		'Basic' => 'Basic structur',
		'Model' => 'Model',
		'Method' => 'Method',
		'Action' => 'Action'
	);
	const TEMPLATE = 'Basic';

	public function buildForm(){

		$form = new \Library\Form( 'list' );
		$form->addElem('form', '', array());

		$validators = \Library\Validate::get('addMethod');

		$elem = array(
			'folder' => 'text',
			'create' => 'select',
			'model_name' => 'select',
			'name' => 'text',
			'addMethod' => 'submit',
		);

		$attr = array(
			'folder' => array(
				'label' => $this->Language('Application folder'),
				'validators' => $validators
			),
			'create' => array(
				'label' => $this->Language('Create by template'),
				'option' => self::CREATE
			),
			'model_name' => array(
			),
			'name' => array(
				'validators' => $validators
			),
			'addMethod' => array(
				'value' => $this->Language('Add method'),
				'label' => ''
			),
		);

		$form->addElem($elem, '', $attr);

		$form->selected( $_POST['create'], 'create' );

		$form->errorLabel( $this->getError() );
		$form->messageLabel( $this->getMessage() );

		$form->setData( $_POST );
		$form->toString();
	}
}

?>