<?
namespace {namespace};

class Index extends \Library{

	public function addForm(){

		$form = new \Library\Form( 'list' );
		$form->addElem('form', '', array(
			'action' => ''
		));

		$form->addElem('text', 'name', array(
			'label' => $this->Language('Name'),
			'value' => $_POST['name']
		));

		$form->addElem('submit', 'add', array(
			'value' => $this->Language('Add')
		));

		$form->addElem('a', '', array(
			'href' => './',
			'value' => $this->Language('Cancel'),
			'style' => 'margin-left:20px;'
		))->after('add');

		//use next lines in view
		$form->errorLabel( $this->getError() );
		$form->messageLabel( $this->getMessage() );

		$form->setData( $_POST );
		$form->toString();
	}
}

?>