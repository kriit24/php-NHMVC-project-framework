<?
namespace Command\Form;

class Form extends \Library{

	const FORM = array(
		'add' => 'addForm',
		'addColumn' => 'addColumn',
		'updateColumn' => 'updateColumn',
		'deleteColumn' => 'deleteColumn',
	);

	function createForm(){
		
		$form = new \Library\Form( 'list' );
		$form->addElem('form');

		$form->addElem('text', 'route_name', array(
			'label' => _tr('Route name'),
			'required' => 'true',
			'required-label' => _tr( 'Route name' )
		));

		$form->addElem('text', 'app_name', array(
			'label' => _tr('App name'),
			'required' => 'true'
		));

		$form->addElem('text', 'form_name', array(
			'label' => _tr('Form name'),
			'required' => 'true'
		));


		$form->addElem('text', 'column_type', array(
			'label' => 'Column type, name, label',
			'class' => 'column-name form-control',
			'required' => 'true'
		));

		$form->addElem('text', 'column_name', array(
			'class' => 'column-name form-control',
			'required' => 'true'
		))->after('column_type');

		$form->addElem('text', 'column_label', array(
			'class' => 'column-name form-control',
		))->after('column_type');

		$form->addElem('submit', self::FORM['addColumn'], array(
			'value' => _tr('Add column'),
			'class' => 'column-name btn btn-primary',
		))->after('column_type');


		if( $columns = \Session::formColumns() ){

			foreach($columns as $k => $row){

				$form->addElem('text', 'update[column_type][]', array(
					'value' => $row->column_type,
					'class' => 'column-name form-control',
				));

				$form->addElem('text', 'update[column_name][]', array(
					'value' => $row->column_name,
					'class' => 'column-name form-control',
				))->after('update[column_type][]');

				$form->addElem('text', 'update[column_label][]', array(
					'value' => $row->column_label,
					'class' => 'column-name form-control',
				))->after('update[column_type][]');

				$form->addElem('submit', self::FORM['updateColumn'], array(
					'value' => _tr('Update column'),
					'class' => 'column-name  btn btn-primary',
				))->after('update[column_type][]');

				$form->addElem('a', self::FORM['deleteColumn'], array(
					'href' => $this->url('?action=deleteColumn&id='.$k),
					'value' => _tr('Delete column'),
					'class' => 'column-name confirm',
				))->after('update[column_type][]');
			}
		}


		$form->addElem('submit', self::FORM['add'], _tr('Add Form'));

		$form->addElem('a', 'clear', array(
			'href' => '?action=clearForm',
			'value' => _tr('Clear'),
			'style' => 'margin-left:5px;'
		))->after(self::FORM['add']);

		$form->errorLabel( $this->getError() );
		$form->messageLabel( $this->getMessage() );

		$post = $_POST;
		unset($post['column_type']);
		unset($post['column_name']);
		unset($post['column_label']);

		$form->setData($post);
		$form->toString();

		$this->script("$('.confirm').confirm('"._tr('Delete Column ?')."');");
	}
}

?>