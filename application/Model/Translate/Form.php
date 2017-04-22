<?
namespace Model\Translate;

class Form extends \Library{

	const SUBMIT = array(
		'update' => 'updateLanguage'
	);
	const URL = array(
		'cancel' => array('model' => 'Translate', 'method' => 'Translate')
	);

	public function SelectLanguage(){

		$form = new \Library\Form();
		$form->addElem('select', 'byLanguage', array(
			'option' => array_values(\Conf\Conf::LANGUAGE)
		))->selected( $_GET['byLanguage'] );
		
		$form->addElem('a', 'truncate', array(
			'href' => $this->url('?action=truncate'),
			'class' => 'truncate btn btn-primary',
			'value' => $this->Language('Truncate')
		));

		return $form->getString();
	}

	public function TranslateForm(){

		$form = new \Library\Form( 'row' );

		$form->addElem('data', 'clear_name', array(
			'label' => $this->Language('Name'),
			'label-attr' => array('class' => 'name')
		));

		$form->addElem('data', 'clear_value', array(
			'label' => $this->Language('Content'),
			'label-attr' => array('class' => 'value2')
		));

		$form->addElem('data', 'model2', array(
			'label' => $this->Language('Model'),
			'label-attr' => array('class' => 'model2')
		));

		$form->addElem('a', 'delete', array(
			'value' => $this->Language('Delete'),
			'href' => $this->url( '?action=delete&id{id}' ),
			'class' => "delete"
		));

		return $form;
	}

	public function EditForm( $row ){

		$form = new \Library\Form( 'list' );
		$form->addElem('form', '', array('action' => $this->url('?id='.$_GET['id'])));
		$form->addElem('hidden', 'name', array('value' => $row['name']))->append('form');
		$form->addElem('hidden', 'model', array('value' => $row['model']))->append('form');

		$form->addElem('data', 'clear_name', array(
			'label' => $this->Language('Name'),
		));

		$form->addElem('data', 'model', array(
			'label' => $this->Language('Model')
		));
		foreach(\Conf\Conf::LANGUAGE as $lang){

			$form->addElem('textarea', $lang, array(
				'label' => $lang
			));
		}
		
		$form->addElem('checkbox', 'update_all_with_same_name', array(
			'value' => 1,
		))->after( '<span style="margin-left:5px;">' . $this->Language( 'Update all with same value' ) . '</span>' );
		
		$form->addElem('submit', Form::SUBMIT['update'], 'Update');

		$form->setData( $row );
		$form->toString();
	}

	public function TranslateFilter(){

		$rows = \Table\language::singleton()->Select()
			->column("model")
			->group("model")
			->fetchall();

		foreach($rows as $v)
			$models[][$v['model']] = $v['model'];

		$form = new \Library\Form( 'list' );
		$form->addElem('text', 'name', array(
			'label' => $this->Language('Name'),
			'class' => 'autocomplete form-control',
			'data-href' => $this->url( array('route' => 'Helper', 'helper' => 'Autocomplete', 'method' => 'Index', 'action' => 'Translate', 'getBy' => 'name') )
		));
		$form->addElem('text', 'value2', array(
			'label' => $this->Language('Value'),
			'class' => 'autocomplete form-control',
			'data-href' => $this->url( array('route' => 'Helper', 'helper' => 'Autocomplete', 'method' => 'Index', 'action' => 'Translate', 'getBy' => 'value') )
		));
		$form->addElem('select', 'model2', array(
			'option' => $models,
			'label' => $this->Language('Model'),
		));
		return $form;
	}
}

?>