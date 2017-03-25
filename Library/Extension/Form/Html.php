<?
namespace Library\Extension\Form;

class Html{

	const ELEMENTS = array(
		'script' => 'script',
		'form' => 'form',
		'table' => 'table',
		'thead' => 'thead',
		'tbody' => 'tbody',
		'tr' => 'tr',
		'th' => 'th',
		'td' => 'td',
		'div' => 'div',
		'span' => 'span',
		'br' => '/br',
		'a' => 'a',
		'i' => 'i',
		'h1' => 'h1',
		'h2' => 'h2',
		'h3' => 'h3',
		'h4' => 'h4',
		'h5' => 'h5',
		'h6' => 'h6',
		'img' => 'img',
		'iframe' => 'iframe',
		'label' => 'label',
		'/script' => '/script',
		'/form' => '/form',
		'/table' => '/table',
		'/thead' => '/thead',
		'/tbody' => '/tbody',
		'/tr' => '/tr',
		'/th' => '/th',
		'/td' => '/td',
		'/div' => '/div',
		'/span' => '/span',
		'/a' => '/a',
		'/i' => '/i',
		'/h1' => '/h1',
		'/h2' => '/h2',
		'/h3' => '/h3',
		'/h4' => '/h4',
		'/h5' => '/h5',
		'/h6' => '/h6',
		'/img' => null,
		'/iframe' => '/iframe',
		'/label' => '/label',
	);
	const ELEMENT_DEFAULT_ATTR = array(
		'form' => array('method' => 'POST'),
		'script' => array('type' => 'text/javascript')
	);

	private $data = array();
	private $elemAfter = array();

	public function setData($data){

		$this->data = $data;
	}

	public function getObjects( $elem ){

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

		if( isset($attr['complete']) )
			unset($attr['complete']);

		return $this->attr(
			(!is_Array($attr) ? array() : $attr),
			array(
				'elem' => self::ELEMENTS[$type],
				'className' => 'Html',
				'type' => $type,
				'name' => ($elemName ? $elemName : $type),
				'value' => (!is_Array($attr) ? $attr : '')
			)
		);
	}

	function attr($attr, $elemArray){

		unset($attr['value']);
		return array_merge($elemArray, array('attr' => array_merge( (!empty($elemArray) && !empty($elemArray['attr']) ? $elemArray['attr'] : array()), $attr )));
	}

	private function buildAttr($elem){

		if( self::ELEMENT_DEFAULT_ATTR[ $elem['elem'] ] )
			$elem['attr'] = array_merge(self::ELEMENT_DEFAULT_ATTR[ $elem['elem'] ], $elem['attr']);

		if( empty($elem['attr']) )
			return '';

		$html = '';
		foreach($elem['attr'] as $k => $v){

			if( isset($v) && $v != null && !is_Array($v) )
				$html .= ' ' . $k . '="' . $v . '"';
		}
		return $html;
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

	private function getData($elem){

		if( is_array($this->data) ){

			if( isset($this->data[ $elem['name'] ]) )
				$elem['value'] = $this->data[ $elem['name'] ];
			return $elem;
		}
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

	private function onComplete( $elem ){

		if( $elem['complete'] ){

			$fn = $elem['complete'];
			$fnAttr = $elem['attr'];
			$fnAttr['value'] = $elem['value'];
			$ret = $fn( $this->data, $fnAttr );
			if( $ret ){

				if( $ret['value'] ){

					$elem['value'] = $ret['value'];
					unset($ret['value']);
				}
				$elem['attr'] = array_merge($elem['attr'], $ret);
			}
		}

		return $elem;
	}

	private function openTag($elem){

		$elem = $this->onComplete($elem);

		if( !isset($elem['value']) || empty($elem['value']) )
			$elem = $this->getData($elem);

		$retElem = $this->buildSiblingElem($elem['before']) . '<' . $elem['elem'] . $this->buildAttr($elem) . '>' . $this->buildSiblingElem($elem['append']) . $elem['value'];
		if( $elem['after'] )
			$this->elemAfter['/'.$elem['elem']] = $elem['after'];
		return $retElem;
	}

	private function closeTag($elem, $shortTag = false){

		$elemAfter = $this->elemAfter[$elem['elem']] ? $this->elemAfter[$elem['elem']] : array();
		unset($this->elemAfter[$elem['elem']]);
		if( json_encode($elem['after']) != json_encode($elemAfter))
			$elemAfter = array_merge($elemAfter, ($elem['after'] ? $elem['after'] : array()));

		return ($shortTag ? '' : '<' . $elem['elem'] . '>') . $this->buildSiblingElem($elemAfter);
	}

	private function shortTag($elem){

		$elemHtml = $this->openTag($elem);
		$elemHtml .= $this->closeTag($elem, true);
		return $elemHtml;
	}

	private function openCloseTag($elem){

		$retElem = $this->openTag($elem);
		$elem['elem'] = '/'.$elem['elem'];
		$retElem .= $this->closeTag($elem);
		return $retElem;
	}

	private function _a($elem){

		return $this->openCloseTag($elem);
	}

	private function _i($elem){

		return $this->openCloseTag($elem);
	}

	private function _span($elem){

		return $this->openCloseTag($elem);
	}

	private function _img($elem){

		return $this->shortTag($elem);
	}

	private function _iframe($elem){

		return $this->openCloseTag($elem);
	}

	private function _label($elem){

		return $this->openCloseTag($elem);
	}

	function __call($method, $args){

		$data = $this->data;

		if( method_Exists($this, '_'.$method) )
			return \Library\Component\Replace::replace(
				$this->{'_'.$method}((is_array($args[0]) ? array_merge($args[0], array('elem' => $method)) : array('elem' => $method)) ),
				$data
			);

		if( substr($method, 0, 1) != '/' )
			return \Library\Component\Replace::replace(
				$this->openTag( (is_array($args[0]) ? array_merge($args[0], array('elem' => $method)) : array('elem' => $method)) ),
				$data
			);
		else
			return \Library\Component\Replace::replace(
				$this->closeTag( (is_array($args[0]) ? array_merge($args[0], array('elem' => $method)) : array('elem' => $method)) ),
				$data
			);
	}
}
?>