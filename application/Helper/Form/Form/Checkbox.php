<?
namespace Helper\Form\Form;

class Checkbox{

	/*$form = \Helper\Form\Form\Checkbox::get( $form, 'create_form', array(
		'value' => 1, 
		'label' => $this->Language( 'Create form' ),
		'complete' => function($row){

			return array('checked' => $row['is_checked'] ? true : false);
		}
	));
	$form->checked( 1, 'create_form' );
	*/

	public static function get( $form, $name, $attr = array() ){

		$id = uniqid($name . '_');

		$form->addElem('checkbox', $name, array_merge(array(
			'id' => $id,
			'style' => 'visibility:hidden;'
		), $attr));

		$form->addElem('label', $name . '_span', array(
			'for' => $id,
			'class' => 'checkbox',
			'value' => '<span></span>'
		))->after($name);

		return $form;
	}
}

?>