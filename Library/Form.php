<?
namespace Library;

class Form extends Component\isPrivate{

	private $createFooter = true;
	private $bodyType = 'row';//list
	private $tableClass = 'table table-hover';

	use Extension\Form, Extension\Form\Create;

	/*
	$bodyType as string - row,list
	$structur - array('header', 'thead', 'tbody', 'footer')
	*/
	function __construct( $bodyType = '', $structur = array() ){

		$this->bodyType = $bodyType;
		if( $bodyType == 'list' )
			$this->tableClass .= ' table-horizontal';
		if( $bodyType )
			$this->_setStructur($structur);
		$this->construct();
	}

	function addElem($type, $name = '', $attr = array(), $mergeAttr = array(), $return = false){

		if( is_array($attr) && is_array($mergeAttr) )
			$attr = array_merge($attr, $mergeAttr);

		if( is_array($type) )
			return $this->_addFromArray($type, $attr);

		if( is_array($name) )
			return $this->_addFromArray($name, $attr, $type);

		if( is_array($attr) )
			$attr = array_filter($attr, function($value) { return $value !== ''; });
		$elemName = ($name ? $name : $type);
		if( !$return ){

			$this->elemName = $elemName;
			$this->_addElem($type, $elemName, $attr, $return);
			return $this;
		}
		else{

			$elem = $this->_addElem($type, $elemName, $attr, $return);
			return $elem;
		}
	}

	function createElem($type, $name, $class, $attr, $return = false){

		$elemName = ($name ? $name : $type);
		return $this->_createElem($type, $name, $class, $attr, $return);
	}

	function checked($checked = true, $name = ''){

		$elemName = $name ? $name : $this->elemName;
		$this->_checked($elemName, $checked);
		return $this;
	}

	function selected($selected, $name = ''){

		$elemName = $name ? $name : $this->elemName;
		$this->_selected($elemName, $selected);
		return $this;
	}

	function label( $name ){

		return ucfirst(str_replace( array('_', '-'), ' ', $name ));
	}

	function attr($attr, $name = ''){

		$elemName = $name ? $name : $this->elemName;
		$this->_attr($attr, $elemName);
		return $this;
	}

	function errorLabel($error){

		$this->_errorLabel($error);
	}

	function messageLabel($message){

		$this->_messageLabel($message);
	}

	function before($name){

		$this->_before($name, $this->elemName);
	}

	function after($name){

		$this->_after($name, $this->elemName);
	}

	function append($name){

		$this->_append($name, $this->elemName);
	}

	function remove($name){

		$this->_remove($name);
		return $this;
	}

	function setData($data){

		$this->_setData($data);
	}

	function validators($validators){

		$this->_validators($validators);
	}

	function toString( $attr = array() ){

		if( !empty($this->structur) ){

			foreach($this->structur as $v){

				if( $v != 'footer' ){

					$this->_create($v, $attr);
				}
				else{

					if( $this->createFooter )
						$this->_create('footer', array());
					$this->destruct();
				}
			}
		}
		else{

			$this->_createHtmlElements();
			$this->destruct();
		}
	}

	function getString( $attr = array() ){

		$html = '';

		if( !empty($this->structur) ){

			foreach($this->structur as $v){

				if( $v != 'footer' ){

					$html .= $this->_create($v, $attr, true);
				}
				else{

					if( $this->createFooter )
						$html .= $this->_create('footer', array(), true);
					$this->destruct();
				}
			}
		}
		else{

			$html .= $this->_createHtmlElements(true);
			$html .= $this->destruct();
		}
		return $html;
	}

	function getRow($data = array()){

		return $this->_getRow($data);
	}

	function getElements(){

		return $this->elemList;
	}
}

?>