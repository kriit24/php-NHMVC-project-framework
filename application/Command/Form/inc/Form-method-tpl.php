	public function {form_name}(){

		$form = new \Library\Form( 'list' );
		$form->addElem('form');

{method_columns}

		$form->addElem('submit', self::FORM['add'], $this->Language(self::FORM['add']));

		$form->errorLabel( $this->getError() );
		$form->messageLabel( $this->getMessage() );

		$form->setData($_POST);
		$form->toString();
		return $form;
	}