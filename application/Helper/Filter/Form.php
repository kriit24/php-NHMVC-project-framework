<?
namespace Helper\Filter;

class Form extends \Library{

	public function Header($form){

		return $this->buildElem( $form );
	}

	public function Show($form){

		return $this->buildElem( $form );
	}

	public function Group($formArray){

		$retArray = array();
		foreach($formArray as $form){

			$retArray[] = $this->buildElem( $form );
		}
		return $retArray;
	}

	public function tr($elemlist, $key, $row){

		$exp = explode('[', $row['name']);
		$name = $exp[0];

		$ret = '<div class="form-group">'.
			'<label for="filterElem' . ucfirst(str_replace(' ', '_', $name)) . '">'.($row['label'] ? $row['label'] : $name).'</label>'.
			$row['html'].
		'</div>';

		foreach($elemlist as $k => $v){

			$exp = explode('[', $v['name']);
			$name_2 = $exp[0];

			if( $name_2 == $name && $k != $key ){

				$ret .= '<div class="form-group">'.
							'<label for="filterElem' . ucfirst(str_replace(' ', '_', $name_2)) . '">'.($v['label'] ? $v['label'] : $name_2).'</label>'.
							$v['html'].
						'</div>';
				$this->continue[$k] = true;
			}
		}
		return $ret;
	}

	private function buildElem($form){

		$elem = $form->getElements('form');

		foreach($elem as $k => $v){

			$exp = explode('[', $v['name']);
			$exp_2 = explode(']', $exp[1]);
			$name = $exp[0];
			$name_2 = $exp_2[0];

			$elemI[$name] = isset($elemI[$name]) ? $elemI[$name] : 0;
			$elemI[$name] = $name_2 ? $name_2 : $elemI[$name];

			$className = $v['className'];
			$class = $form->$className;
			$method = $v['elem'];
			$value = (is_array($_GET[$name]) ? $_GET[$name][ $elemI[$name] ] : $_GET[$name]);

			$v['attr']['id'] = 'filterElem' . ucfirst(str_replace(' ', '_', $name));
			if( !$v['attr']['class'] )
				$v['attr']['class'] = 'form-control';
			if( $v['elem'] == 'input' && $v['type'] == 'text' ){

				$v['attr']['value'] = $value;
			}
			if( $v['elem'] == 'input' && ($v['type'] == 'radio' || $v['type'] == 'checkbox') ){

				$v['checked'] = $value;
			}
			if( $v['elem'] == 'select' ){

				$v['selected'] = $value;
			}
			if( $v['elem'] == 'textarea' ){

				$v['attr']['value'] = $value;
			}
			$elem[$k]['html'] = $class->$method($v);

			$elemI[$name]++;
		}
		return $elem;
	}
}

?>