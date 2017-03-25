<?
namespace Library\Extension;

trait Form{

	private $elemList = array();
	private $elemsList = array();
	private $elemsType = array();
	private $htmlElemList = array();
	private $elemName = '';
	private $mainAttr = array('label', 'label-attr');
	private $structur = array();
	private $data = array();
	private $_instances = array();
	public $countElems = 0;

	private function construct(){

		$this->Form = new Form\Form($this->_parent);
		$this->Html = new Form\Html($this->_parent);
	}

	private function _setStructur($structur){

		if( empty($structur) ){

			$this->structur = $this->bodyType == 'row' ? array('table', 'thead', 'tbody', 'footer') : array('table', 'tbody', 'footer');
		}
		else{

			$this->structur = $structur;
		}
	}

	private function _setData($data){

		//$this->Form->setData($data);
		//$this->Html->setData($data);
		//$this->data[] = json_encode($data);
		$this->data[] = $data;
	}

	private function getClass($type){

		if( $this->_instances[$type] )
			return $this->_instances[$type];

		if( $this->Form::ELEMENTS[$type] )
			$this->_instances[$type] = $this->{'Form'};
		if( $this->Html::ELEMENTS[$type] )
			$this->_instances[$type] = $this->{'Html'};

		return $this->_instances[$type];
	}

	private function getElement($name){

		if( !isset($this->elemsList[$name]) )
			return '';

		$elemKey = end($this->elemsList[$name]);
		return array($elemKey, $this->elemList[$elemKey]);
	}

	private function _addFromArray($list, $attr, $elemType = null){

		foreach($list as $name => $type){

			if( is_numeric($name) ){

				$name = $type;
				$type = $elemType ? $elemType : 'text';
			}
			if( !isset($attr[$name]['label']) )
				$attr[$name]['label'] = $this->label($name);

			$this->addElem($type, $name, $attr[$name]);
		}
		return $this;
	}

	private function _addElem($type, $name = '', $attr, $return = false){

		$elemAttr = $this->unsetMainAttr($attr);
		$elemClass = $this->getClass($type);
		$elem = array_merge($elemClass->addElem($type, $name, $elemAttr), (!is_array($attr) ? array() : $attr));
		if( $return )
			return $elem;
		$this->elemList[] = $elem;
		$this->elemsList[$name][] = end(array_keys($this->elemList));
		$this->elemsType[$type][] = $name;
		if( $elem['label'] )
			$this->htmlElemList['label'] = true;
		$this->countElems = count($this->elemList);
	}

	private function _createElem($type, $name, $class, $attr, $return = false){

		$elemAttr = $this->unsetMainAttr($attr);
		$elemClass = new $class;
		$elem = array_merge($elemClass->addElem($type, $elemName, $elemAttr), (!is_array($attr) ? array() : $attr));
		if( $return )
			return $elem;
		$this->elemList[] = $elem;
		$this->elemsList[$elemName][] = end(array_keys($this->elemList));
		if( $elem['label'] )
			$this->htmlElemList['label'] = true;
	}

	private function _setElemSibling($addToElement, $fromElem, $siblingType){

		list($elemToKey, $elemTo) = $this->getElement($addToElement);
		list($elemFromKey, $elemFrom) = $this->getElement($fromElem);

		if( $elemFrom['className'] == 'Form' )
			$elemFrom = $this->Form->getObjects( $elemFrom );

		if( $elemFrom['className'] == 'Html' )
			$elemFrom = $this->Html->getObjects( $elemFrom );

		$this->elemList[$elemToKey][$siblingType][] = $elemFrom;
		unset($this->elemList[$elemFromKey]);

		//IF PROBLEMS THEN REPLACE WITH FOREACH WHATS IN ABOVE
		$elemsListKeys = array_flip($this->elemsList[$fromElem]);
		$elemsListKey = $elemsListKeys[$elemFromKey];
		unset($this->elemsList[$fromElem][$elemsListKey]);

		/*foreach($this->elemsList[$fromElem] as $k => $v){

			if( $v == $elemFromKey )
				unset($this->elemsList[$fromElem][$k]);
		}*/
		if( empty($this->elemsList[$fromElem]) )
			unset($this->elemsList[$fromElem]);
	}

	private function _createElemSibling($valueToAdd, $addToElement, $siblingType){

		$this->addElem('stdElem', '', array('value' => $valueToAdd))->{$siblingType}($addToElement);
	}

	private function _before($addToElement, $fromElem){

		if( !$this->getElement($addToElement) )
			$this->_createElemSibling($addToElement, $fromElem, 'before');
		else
			$this->_setElemSibling($addToElement, $fromElem, 'before');
	}

	private function _after($addToElement, $fromElem){

		if( !$this->getElement($addToElement) )
			$this->_createElemSibling($addToElement, $fromElem, 'after');
		else
			$this->_setElemSibling($addToElement, $fromElem, 'after');
	}

	private function _append($addToElement, $fromElem){

		if( !$this->getElement($addToElement) )
			$this->_createElemSibling($addToElement, $fromElem, 'append');
		else
			$this->_setElemSibling($addToElement, $fromElem, 'append');
	}

	private function _remove($name){

		if( isset($this->elemsList[$name]) ){

			foreach($this->elemsList[$name] as $key){

				unset($this->elemList[$key]);
			}
			unset($this->elemsList[$name]);
		}
	}

	private function _checked($name, $checked){

		//list($elemToKey, $elemTo) = $this->getElement($name);

		//$this->elemList[$elemToKey] = $this->Form->checked($elemTo, $checked);
		$this->Form->checked($name, $checked);
		return $this;
	}

	private function _selected($name, $selected){

		//list($elemToKey, $elemTo) = $this->getElement($name);

		//$this->elemList[$elemToKey] = $this->Form->selected($elemTo, $selected);
		$this->Form->selected($name, $selected);
		return $this;
	}

	private function _errorLabel($error){

		echo $this->createErrorLabel($error);
	}

	private function _messageLabel($message){

		echo $this->createMessageLabel($message);
	}

	private function unsetMainAttr($attr){

		foreach($this->mainAttr AS $attrName){

			if( isset($attr[$attrName]) )
				unset($attr[$attrName]);
		}
		return $attr;
	}

	private function _attr($attr, $name){

		//$elemKey = null;
		list($elemKey, $elemArray) = $this->getElement($name);
		//$elemClass = $this->getClass($elemArray['type']);
		//$elemArray = $elemClass->attr($attr, $elemArray);
		//$this->elemList[$elemKey] = $elemArray;
		$this->elemAttr[$elemKey][] = $attr;
	}

	private function _validators($validators){

		foreach($validators as $name => $attr)
			$this->_attr( array('validators' =>  $attr), $name );
	}

	private function _create($type, $attr, $return = false){

		if( !empty($attr) ){

			foreach($attr as $k => $v){

				if( \Library\ArrayIterator::countDimension($v) == 0 ){

					unset($attr[$k]);
					$attr[$type] = array($k => $v);
				}
			}
		}

		if( $return ){

			return $this->createHtml($type, $attr);
		}
		else{

			//echo htmlspecialchars($this->createHtml($type, $attr));
			echo $this->createHtml($type, $attr);
		}
	}

	private function _createHtmlElements($return = false){

		if( $return ){

			//echo htmlspecialchars($this->createHtmlElements());
			return $this->createHtmlElements();
		}
		else{

			echo $this->createHtmlElements();
		}
	}

	private function _getRow($data){

		$this->createFooter = false;
		$obj = clone $this;

		foreach($obj->elemList as $elem){

			if( $data[ $elem['name'] ] ){

				$attr['value'] = $data[ $elem['name'] ];
				//$obj->_attr($attr, $elem['name']);

				$elemKey = null;
				list($elemKey, $elemArray) = $obj->getElement($elem['name']);
				$elemClass = $obj->getClass($elemArray['type']);
				$elemArray = $elemClass->attr($attr, $elemArray);
				$obj->elemList[$elemKey] = $elemArray;
				$obj->elemAttr[$elemKey][] = $attr;
			}
		}
		return $obj;
	}

	private function destruct(){

		$this->elemList = array();
		$this->elemsList = array();
		$this->elemsType = array();
		$this->htmlElemList = array();
		$this->elemName = '';
		$this->data = array();
	}
}

?>