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

		$sql = new \Library\Sql;

		$form = new \Library\Form( 'list' );
		$form->addElem('form', '', array());

		$form->addElem('text', 'folder', array(
			'label' => _tr('Application folder'),
			'required' => 'true',
			'required-label' => _tr( 'Application folder required' )
		));

		$form->addElem('select', 'create', array(
			'label' => _tr('Create by template'),
			'option' => self::CREATE
		))->selected( $_POST['create'] );

		$form->addElem('select', 'model_name', array(
			'label' => _tr('Model name'),
			'' => '',
		));

		$form->addElem('text', 'name', array(
			'label' => _tr('Name'),
			'required' => 'true',
			'required-label' => _tr( 'Name required' )
		));

		$tables = array_merge(
			array(_tr( 'Select' )),
			\Library\ArrayIterator::singleton()->arrayValues($sql->query("SHOW FULL TABLES WHERE Table_Type = 'BASE TABLE'")->fetchAll(), 'Tables_in_' . \Conf\Conf::_DB_CONN['_default']['_database'])
		);

		$form->addElem('select', 'table', array(
			'label' => _tr('Table'),
			'option' => $tables,
		))->selected($_POST['table']);

		$form->addElem('span', 'table_column', array(
			'label' => _tr('Table columns'),
			'class' => 'table-column',
			'value' => '&nbsp;'
		));

		$form->addElem('submit', 'addTableColumns', array(
			'value' => _tr('Add columns'),
		));

		$form->addElem('submit', 'addMethod', array(
			'value' => _tr('Add method'),
		));

		$form->errorLabel( $this->getError() );
		$form->messageLabel( $this->getMessage() );

		$form->setData( $_POST );
		$form->toString();
	}

	public function CreateForm(){

		$form = new \Library\Form( 'list' );

		$form->addElem('form', '', array(
			'' => '',
		));

		$form->addElem('text', 'application_folder', array(
			'label' => _tr('Application folder'),
			'' => '',
		));

		$form->setData($_POST);
		$form->toString();
	}
}

?>