<?
namespace Library\Extension\Form;

class Form{

	const ELEMENTS = array(
		'text' => 'input',
		'checkbox' => 'input',
		'radio' => 'input',
		'submit' => 'input',
		'button' => 'input',
		'color' => 'input',
		'date' => 'input',
		'datetime' => 'input',
		'datetime-local' => 'input',
		'email' => 'input',
		'file' => 'input',
		'hidden' => 'input',
		'image' => 'input',
		'month' => 'input',
		'number' => 'input',
		'password' => 'input',
		'range' => 'input',
		'reset' => 'input',
		'tel' => 'input',
		'time' => 'input',
		'url' => 'input',
		'week' => 'input',
		'textarea' => 'textarea',
		'select' => 'select',
		'option' => 'option',
		'data' => 'data',
		'header' => 'header',
		'stdElem' => 'data',
	);
	const ELEMENT_DEFAULT_ATTR = array(
		'input' => array('class' => 'form-control'),
		'input:submit' => array('class' => 'btn btn-primary'),
		'input:checkbox' => array('class' => ''),
		'select' => array('class' => 'form-control'),
		'textarea' => array('class' => 'form-control'),
	);

	private $data = array();
	private $selected = array();
	private $checked = array();

	public function setData($data){

		$this->data = $data;
	}

	public function getObjects( $elem ){

		if( $this->selected[ $elem['name'] ] && $elem['type'] == 'select' ){

			$elem['options']['selected'] = $this->selected[ $elem['name'] ];
			unset( $this->selected[ $elem['name'] ] );
		}

		if( $this->checked[ $elem['name'] ] && $elem['type'] == 'checkbox' ){

			$elem['options']['checked'] = $this->checked[ $elem['name'] ];
			unset( $this->checked[ $elem['name'] ] );
		}

		return $elem;
	}

	private function getClass($elem, $type){

		if( isset(Form::ELEMENTS[$type]) )
			return new Form();
		if( isset(Html::ELEMENTS[$type]) )
			return new Html();

		die('ELEMENT NOT FOUND:' . $elem . ' TYPE ' . $type);
	}

	function addElem($type, $elemName, $attr){

		if( empty($attr) )
			$attr = array();

		if( isset($attr['option']) )
			unset($attr['option']);
		if( isset($attr['optgroup']) )
			unset($attr['optgroup']);
		if( isset($attr['complete']) )
			unset($attr['complete']);
		if( isset($attr['validators']) ){

			$attr['required'] = 'required';
			$attr['required-label'] = $attr['validators'][$elemName];
			unset($attr['validators']);
		}

		if( is_Array($attr) ){

			preg_match_all('/([a-z_]+)\-attr([0-9\(\)_]+)|([a-z_]+)\-attr/s', implode(';', array_keys($attr)), $matches);
			if( !empty($matches[0]) ){

				foreach($matches[0] as $key)
					unset($attr[ $key ]);
			}
		}

		return $this->attr(array_merge(array(
			'type' => (self::ELEMENTS[$type] == 'input' ? $type : ''),
			'name' => $elemName,
			'value' => (!is_Array($attr) ? $attr : ''),
		), (!is_Array($attr) ? array() : $attr)), 
			array(
				'elem' => self::ELEMENTS[$type],
				'className' => 'Form',
				'type' => $type,
				'name' => $elemName
			)
		);
	}

	function attr($attr, $elemArray){

		return array_merge($elemArray, array('attr' => array_merge( (!empty($elemArray) && !empty($elemArray['attr']) ? $elemArray['attr'] : array()), $attr )));
	}

	private function buildAttr($elem){

		if( isset(self::ELEMENT_DEFAULT_ATTR[ $elem['elem'] ]) || isset(self::ELEMENT_DEFAULT_ATTR[ $elem['elem'] .':'. $elem['type'] ]) ){

			$attr = array();

			if( isset(self::ELEMENT_DEFAULT_ATTR[ $elem['elem'] ]) ){

				$attr = self::ELEMENT_DEFAULT_ATTR[ $elem['elem'] ];
			}
			if( isset(self::ELEMENT_DEFAULT_ATTR[ $elem['elem'] .':'. $elem['type'] ]) ){

				$attr = self::ELEMENT_DEFAULT_ATTR[ $elem['elem'] .':'. $elem['type'] ];
			}
			$elem['attr'] = array_merge($attr, $elem['attr']);
		}

		if( empty($elem['attr']) )
			return '';

		if( isset($elem['attr']['validators']) && !isset($elem['attr']['required-label']) ){

			$elem['attr']['required'] = 'required';
			$elem['attr']['required-label'] = $elem['attr']['validators'];
			unset($elem['attr']['validators']);
		}

		$html = '';
		foreach($elem['attr'] as $k => $v){

			if( isset($v) && $v != null && !is_Array($v) )
				$html .= ' ' . $k . '="' . $v . '"';
		}
		return $html;
	}

	private function getElemAttr($elem, $type, $i = 0){

		$ret = array();
		if( $elem[ $type ] ){

			$ret = $elem[ $type ];
		}
		if( $elem[ $type ][ $i ] ){

			$ret = $elem[ $type ][ $i ];
		}
		return $ret;
	}

	private function buildSiblingElem($elems){

		if( is_Array($elems) ){

			$elemHtml = '';

			foreach($elems as $elem){

				$elemClass = $this->getClass($elem['elem'], $elem['type']);
				$elemClass->setData($this->data);
				if( $elem['options'] ){

					foreach($elem['options'] as $optionName => $optionValue){

						$elemClass->{$optionName}( $elem['name'], $optionValue );
					}
				}
				$elemHtml .= $elemClass->{$elem['elem']}($elem);
			}
			return $elemHtml;
		}
	}

	private function getElem($elem){
		
		if( is_object($this->data) ){

			$classIterator = new \Library\classIterator;
			$elemsList = $classIterator->getPropertyValue($this->data, 'elemsList');
			$elemList = $classIterator->getPropertyValue($this->data, 'elemList');
			
			//overwrite object
			if( $elemsList[ $elem['name'] ] )
				$elem = $elemList[ $elemsList[ $elem['name'] ][0] ];
			return $elem;
		}
		return $elem;
	}

	private function getData($elem){
		
		if( is_array($this->data) ){

			$data = $this->data;
			if( isset($data[ $elem['name'] ]) ){

				$elem['attr']['value'] = $data[ $elem['name'] ];
			}
			return $elem;
		}
		return $elem;
	}

	private function onComplete( $elem ){

		if( $elem['complete'] ){

			$elem['attr']['elem'] = $elem['elem'];

			$fn = $elem['complete'];
			$ret = $fn( $this->data, $elem['attr'] );
			if( $ret ){

				unset($ret['elem']);
				unset($elem['attr']['elem']);
				$elem['attr'] = array_merge($elem['attr'], $ret);
			}
		}

		return $elem;
	}

	function selected($elem, $selected){

		$this->selected[$elem][][$selected] = true;
	}

	function checked($elem, $checked){

		$this->checked[$elem][][$checked] = true;
	}

	private function createElem($elem){

		$elem = $this->onComplete($elem);

		if( $elem['type'] != 'submit' && (!isset($elem['attr']['value']) || empty($elem['attr']['value'])) )
			$elem = $this->getData($elem);

		$preValue = preg_match('/\{(.*?)\}/i', $elem['attr']['value']) ? \Library\Component\Replace::replace($elem['attr']['value'], $this->data) : $elem['attr']['value'];
		$checked = isset($this->checked[ $elem['name'] ]) ? array_shift($this->checked[ $elem['name'] ]) : array();
		$checked = $elem['checked'] ? array($elem['checked'] => true) : $checked;
		//maybe resolve this with ::checkedComplete

		if( $checked[ $preValue ] )
			$elem['attr']['checked'] = 'checked';
		return $this->buildSiblingElem($elem['before']) . '<' . $elem['elem'] . $this->buildAttr($elem) . '/>' . $this->buildSiblingElem($elem['after']) . $this->buildSiblingElem($elem['append']);
	}

	private function _data($elem){

		$elem = $this->onComplete($elem);

		if( !isset($elem['attr']['value']) || empty($elem['attr']['value']) )
			$elem = $this->getData($elem);

		return $this->buildSiblingElem($elem['before']) . $elem['attr']['value'] . $this->buildSiblingElem($elem['after']) . $this->buildSiblingElem($elem['append']);
	}

	private function _select($elem){

		$elem = $this->onComplete($elem);

		if( !isset($this->selected[ $elem['name'] ]) ){

			if( is_array($this->data) ){

				if( isset($this->data[ $elem['name'] ]) ){

					$val = $this->data[ $elem['name'] ];
					$this->selected[ $elem['name'] ][][ $val ] = true;
				}
			}
		}

		return $this->buildSiblingElem($elem['before']) . '<' . $elem['elem'] . $this->buildAttr($elem) . '>' . ($elem['optgroup'] ? $this->optgroup($elem) : $this->option($elem)) . '</' . $elem['elem'] . '>' . $this->buildSiblingElem($elem['after']) . $this->buildSiblingElem($elem['append']);
	}

	private function _textarea($elem){

		$elem = $this->onComplete($elem);

		if( !isset($elem['attr']['value']) || empty($elem['attr']['value']) )
			$elem = $this->getData($elem);

		$value = $elem['attr']['value'];
		unset($elem['attr']['value']);
		return $this->buildSiblingElem($elem['before']) . '<' . $elem['elem'] . $this->buildAttr($elem) . '>' . $value . '</' . $elem['elem'] . '>' . $this->buildSiblingElem($elem['after']) . $this->buildSiblingElem($elem['append']);
	}

	private function optgroup($elem){

		if( !$elem['optgroup'] )
			return '';

		$optgroup = '';
		$i = 0;

		foreach($elem['optgroup'] as $k => $v){

			if( !is_Array($v) ){

				if( \Library\ArrayIterator::isAssociative($elem['optgroup']) )
					$v = array($k, $v);
				else
					$v = array($v, $v);
			}
			$optgroupArray = array_values($v);
			$optgroupAttr = array_merge(
				$this->getElemAttr($elem, 'optgroup-attr', $i),
				array('label' => $k)
			);

			$elem['elem'] = 'optgroup';
			$elem['attr'] = $optgroupAttr;
			$elem = $this->onComplete($elem);

			$optionElem = array_merge($elem, array(
				'option' => $optgroupArray,
				'option-attr' => $elem['option-attr']
			));
			$optgroup .= '<optgroup' . $this->buildAttr($elem) . '>' . $this->option($optionElem) . '</optgroup>';
			$i++;
		}
		return $optgroup;
	}

	private function option($elem){

		if( !$elem['option'] )
			return '';

		$option = '';
		$selected = isset($this->selected[ $elem['name'] ]) ? array_shift($this->selected[ $elem['name'] ]) : array();
		$selected = $elem['selected'] ? array($elem['selected'] => true) : $selected;
		$i = 0;

		foreach($elem['option'] as $k => $v){

			if( !is_Array($v) ){

				if( \Library\ArrayIterator::isAssociative($elem['option']) )
					$v = array($k, $v);
				else
					$v = array($v, $v);
			}
			if( !is_numeric(array_keys($v)[0]) && count($v) == 1 ){

				$tmp = $v;
				$v = array_keys($tmp);
				$v = array_merge($v, array_values($tmp));
			}

			$optionArray = array_values($v);
			$optionAttr = array_merge(
				$this->getElemAttr($elem, 'option-attr', $i),
				array(
					'value' => $optionArray[0],
					'selected' => ($selected[ $optionArray[0] ] ? 'selected' : '')
				)
			);

			$elem['elem'] = 'option';
			$elem['attr'] = $optionAttr;
			$elem = $this->onComplete($elem);

			$option .= '<option' . $this->buildAttr($elem) . '>' . $optionArray[1] . '</option>';
			$i++;
		}
		return $option;
	}

	function __call($method, $args){

		$elem = $args[0];
		$elem = $this->getElem($elem);
		$method = $elem['elem'];

		if( method_Exists($this, '_'.$method) )
			return \Library\Component\Replace::replace(
				$this->{'_'.$method}($elem),
				$this->data
			);

		return \Library\Component\Replace::replace(
			$this->createElem( (is_array($elem) ? array_merge($elem, array('elem' => $method)) : array('elem' => $method)) ),
			$this->data
		);
	}
}
?>