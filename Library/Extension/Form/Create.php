<?
namespace Library\Extension\Form;

trait Create{

	private function getHtmlElemAttr($elem, $type, $subtype = null, $i = null){

		$ret = array();
		$retSub = array();

		if( $elem[ $type ] )
			$ret = $elem[ $type ];
		if( isset($i) && $elem[ $type ][ $i ] )
			$ret = $elem[ $type ][ $i ];

		$retSub = $ret;
		if( isset($subtype) && $retSub[ $subtype ] )
			unset($retSub[ $subtype ]);

		if( gettype($ret) == 'string' ){

			pre($elem);
			pre($ret);

			die( '<b style="color:red;">Attribute must be array, string given</b>' );
		}
		return array($retSub, $ret);
	}

	private function createForm($attr){

		if( isset($this->elemsType['form']) ){

			$name = $this->elemsType['form'][0];

			list($eKey, $e) = $this->getElement($name);
			if( isset($this->elemsType['file']) )
				$e['attr']['enctype'] = 'multipart/form-data';

			if( $this->elemAttr[$eKey][0] )
				$e['attr'] = array_merge( ($e['attr'] ?: array()), $this->elemAttr[$eKey][0]);

			$this->_remove($name);

			$dataArray = $this->data;
			$data = $dataArray[0];
			//$data = json_decode($dataArray[0], true);
			//die(pre($dataArray));

			$this->Form->setData($data);
			$this->Html->setData($data);

			return $this->Html->{'form'}($e);
		}
		return false;
	}

	private function createTable($attr){

		$attr['table'] = array_merge(array('class' => $this->tableClass), ($attr['table'] ?? array()));
		list($attrTable, ) = $this->getHtmlElemAttr($attr, 'table');
		return $this->Html->{'table'}($this->Html->addElem('table', '', $attrTable));
	}

	private function createHead($attr){

		if( !$this->htmlElemList['label'] )
			return '';

		list($attrTHead, $attr) = $this->getHtmlElemAttr($attr, 'thead', 'tr');
		list($attrTr, $attr) = $this->getHtmlElemAttr($attr, 'tr', 'th');

		$html = $this->Html->{'thead'}($this->Html->addElem('thead', '', $attrTHead));
		$html .= $this->Html->{'tr'}($this->Html->addElem('tr', '', $attrTr));

		$i = 0;

		foreach($this->elemList as $elem){

			list($attrLabel, ) = $this->getHtmlElemAttr($elem, 'label-attr', $i);

			//$labelValue = $elem['label'] && strip_tags($elem['label']) == $elem['label'] ? $this->Language($elem['label']) : $elem['label'];
			$labelValue = $elem['label'] && strip_tags($elem['label']) == $elem['label'] ? $elem['label'] : $elem['label'];
			if( !empty($attr) ){

				list($setLabelAttr, ) = $this->getHtmlElemAttr($attr, 'th', '', $i);
				$attrLabel = !empty($attrLabel) ? array_merge($attrLabel, $setLabelAttr) : $setLabelAttr;
			}
			$html .= $this->Html->{'th'}(array_merge($this->Html->addElem('th', '', $attrLabel), array('value' => ($elem['label'] ? $labelValue : ''))));
			$html .= $this->Html->{'/th'}();
			$i++;
		}
		$html .= $this->Html->{'/tr'}();
		$html .= $this->Html->{'/thead'}();
		return $html;
	}

	private function createRow($attr, $k, $htmlIn = null){

		list($attrTbody, $attr) = $this->getHtmlElemAttr($attr, 'tbody', 'tr');
		list($attrTr, $attr) = $this->getHtmlElemAttr($attr, 'tr', 'td', $k);

		if( !$this->htmlElemList['tbody'] && $this->htmlElemList['table'] ){

			$html = $this->Html->{'tbody'}($this->Html->addElem('tbody', '', $attrTbody));
			$html .= $this->Html->{'tr'}($this->Html->addElem('tr', 'tr', $attrTr));

			$this->htmlElemList['tbody'] = true;
		}
		else{

			$html .= $this->Html->{'tr'}($this->Html->addElem('tr', 'tr', $attrTr));
		}

		$i = 0;
		if( $htmlIn ){

			$elemHtml = $htmlIn;

			list($attrTd, ) = $this->getHtmlElemAttr($attr, 'td', '', $i);

			$html .= $this->Html->{'td'}(array_merge($this->Html->addElem('td', '', $attrTd), array('value' => $elemHtml)));
			$html .= $this->Html->{'/td'}();
		}
		else{

			foreach($this->elemList as $key => $elem){

				$elemAttr = isset($this->elemAttr[$key][$k]) ? $this->elemAttr[$key][$k] : array();
				$elem['attr'] = array_merge($elem['attr'], $elemAttr);
				$elemHtml = $this->createHtmlElement($elem);

				list($attrTd, ) = $this->getHtmlElemAttr($attr, 'td', '', $i);

				$html .= $this->Html->{'td'}(array_merge($this->Html->addElem('td', '', $attrTd), array('value' => $elemHtml)));
				$html .= $this->Html->{'/td'}();
				$i++;
			}
		}
		$html .= $this->Html->{'/tr'}();
		return $html;
	}

	private function createList($attr){

		$html = '';
		list($attrTbody, $attr) = $this->getHtmlElemAttr($attr, 'tbody', 'tr');
		if( !$this->htmlElemList['tbody'] && $this->htmlElemList['table'] )
			$html = $this->Html->{'tbody'}($this->Html->addElem('tbody', '', $attrTbody));
		$i = 0;
		foreach($this->elemList as $key => $elem){

			$elemAttr = isset($this->elemAttr[$key][0]) ? $this->elemAttr[$key][0] : array();
			$elem['attr'] = array_merge($elem['attr'], $elemAttr);
			$elemHtml = $this->createHtmlElement($elem);

			list($attrTr, $attr) = $this->getHtmlElemAttr($attr, 'tr', 'td', $i);
			list($attrTd, ) = $this->getHtmlElemAttr($attr, 'td', '', $i);
			list($attrLabel, ) = $this->getHtmlElemAttr($elem, 'label-attr');

			$labelValue = $elem['label'] && strip_tags($elem['label']) == $elem['label'] ? $elem['label'] : $elem['label'];
			$thAttr = $attrLabel;
			$tdAttr = $attrTd;

			if( $this->htmlElemList['label'] ){

				$html .= $this->Html->{'tr'}($this->Html->addElem('tr', '', $attrTr));
				$html .= $this->Html->{'th'}(array_merge($this->Html->addElem('th', '', $thAttr), array('value' => ($elem['label'] ? $labelValue : ''))));
				$html .= $this->Html->{'/th'}();
				$html .= $this->Html->{'td'}(array_merge($this->Html->addElem('td', '', $tdAttr), array('value' => $elemHtml)));
				$html .= $this->Html->{'/td'}();
				$html .= $this->Html->{'/tr'}();
			}
			else{

				$html .= $this->Html->{'tr'}($this->Html->addElem('tr', '', $attrTr));
				$html .= $this->Html->{'td'}(array_merge($this->Html->addElem('td', '', $tdAttr), array('value' => $elemHtml)));
				$html .= $this->Html->{'/td'}();
				$html .= $this->Html->{'/tr'}();
			}
			$i++;
		}
		return $html;
	}

	//CREATE HTML ARRAY
	/**
	* shows error label
	* @param Array $error AS $this->error object
	* @param String $clear AS 'none' or 'left' or 'right' or 'both'
	*/
	private function createErrorLabel($error, $return = false){

		$html = '';
		$list = array();

		if( gettype($error) == 'string' && is_Array(json_decode($error, true)) )
			$error = json_decode($error, true);

		if( !is_array($error) && $error )
			$error = array($error);

		if(is_array($error)){

			$html .= $this->Html->{'div'}($this->Html->addElem('div', '', array('class' => 'col error-label', 'style' => 'background:#ffecec;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;display: block;margin-bottom: 5px;padding: 10px 15px;')));

			foreach($error as $k => $v){

				$elem .= $elem ? ',[name='.str_replace(' ', '_', $k).']' : '[name='.str_replace(' ', '_', $k).']';
				if($v && !in_array($v, $list)){

					$html .= $this->Html->{'h4'}( 
						array_merge(
							$this->Html->addElem('h4', '', array('style' => 'margin:0.5rem;font-size:20px;font-weight:bold;')),
							array('value' => $v)
						)
					);
					$html .= $this->Html->{'/h4'}();
				}
			}

			$html .= $this->Html->{'/div'}();

			if( $elem ){

				$html .= $this->Html->{'script'}($this->Html->addElem('script', '', array('value' => '$(document).ready(function(){$("'.$elem.'").addClass("errorLabel");});')));
				$html .= $this->Html->{'/script'}();
			}
		}
		return $html;
	}

	/**
	* show message label
	* @param Array $message AS $this->message object
	*/
	private function createMessageLabel($message, $return = false){

		$html = '';
		$list = array();

		if( gettype($message) == 'string' && is_Array(json_decode($message, true)) )
			$message = json_decode($message, true);

		if( !is_array($message) && $message )
			$message = array($message);

		if(is_array($message)){

			$html .= $this->Html->{'div'}($this->Html->addElem('div', '', array('class' => 'col message-label', 'style' => 'background:#ecffec;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;display: block;margin-bottom: 5px;padding: 10px 15px;')));

			foreach($message as $k => $v){

				$elem .= $elem ? ',[name='.str_replace(' ', '_', $k).']' : '[name='.str_replace(' ', '_', $k).']';
				if($v && !in_array($v, $list)){

					$html .= $this->Html->{'h4'}( 
						array_merge(
							$this->Html->addElem('h4', '', array('style' => 'margin:0.5rem;font-size:20px;font-weight:bold;')),
							array('value' => $v)
						)
					);
					$html .= $this->Html->{'/h4'}();
				}
				$list[] = $v;
			}

			$html .= $this->Html->{'/div'}();

			if($elem){

				$html .= $this->Html->{'script'}($this->Html->addElem('script', '', array('value' => '$(document).ready(function(){$("'.$elem.'").addClass("messageLabel");});')));
				$html .= $this->Html->{'/script'}();
			}
		}
		return $html;
	}

	private function createHtmlElements(){

		$html = '';
		$i = 0;

		foreach($this->elemList as $elem){

			if( !empty($this->data) ){

				foreach($this->data as $data){

					//$data = json_decode($json, true);

					$this->Form->setData($data);
					$this->Html->setData($data);

					$html .= $this->createHtmlElement($elem);
				}
			}
			else{

				$html .= $this->createHtmlElement($elem);
			}
			$i++;
		}

		if( isset($this->elemsType['form']) ){

			$html .= $this->Html->{'/form'}();
		}
		return $html;
	}

	private function createHtmlElement($elem){

		$elemHtml = '';
		$elemClass = $this->getClass($elem['type']);
		$elemHtml .= $elemClass->{$elem['elem']}($elem);
		return $elemHtml;
	}

	private function createHtml($type, $attr){

		$html = '';
		if( empty($this->elemList) && $type != 'footer' )
			return $html;

		if( $type == 'table' ){

			if( $form = $this->createForm($attr) ){

				$html .= $form;
				$this->htmlElemList['form'] = true;
			}

			$html .= $this->createTable($attr);
			$this->htmlElemList['table'] = true;
		}

		if( $type == 'thead' ){

			$html .= $this->createHead($attr);
		}
		if( $type == 'tbody' ){

			if( $this->bodyType == 'list' ){

				if( !empty($this->data) ){

					$dataArray = $this->data;
					$data = $dataArray[0];
					//$data = json_decode($dataArray[0], true);
					//die(pre($dataArray));

					$this->Form->setData($data);
					$this->Html->setData($data);
				}

				$html .= $this->createList($attr);
			}
			if( $this->bodyType == 'row' ){

				if( !empty($this->data) ){

					$dataArray = $this->data;
					//die(pre($dataArray));
				
					$k = 0;
					foreach($dataArray as $data){

						//$data = json_decode($json, true);

						if( gettype($data) == 'array' ){

							$this->Form->setData($data);
							$this->Html->setData($data);

							$html .= $this->createRow( $attr, $k );
							$k++;
						}
						if( gettype($data) == 'string' || strtolower(gettype($data)) == 'null' ){

							$html .= $this->createRow( $attr, $k, $data );
							$k++;
						}
						if( gettype($data) == 'object' ){

							$htmlObject = $data;
							$html .= $htmlObject->createRowFromObject( $attr, $k );
						}
					}
				}
			}
		}
		if( $type == 'footer' ){

			$html .= $this->createFooter();
			$this->createFooter = false;
		}
		return $html;
	}

	private function createRowFromObject( $attr, &$k ){

		$html = '';
		if( !empty($this->data) ){

			foreach($this->data as $data){

				//$data = json_decode($json, true);

				$this->Form->setData( $data );
				$this->Html->setData( $data );

				$html .= $this->createRow( $attr, $k );
				$k++;
			}
		}
		else{

			$html .= $this->createRow( $attr, $k );
			$k++;
		}
		return $html;
	}

	private function createFooter(){

		if( $this->htmlElemList['table'] ){

			$html = '';
			if( $this->htmlElemList['tbody'] )
				$html .= $this->Html->{'/tbody'}();
			$html .= $this->Html->{'/table'}();
		}

		if( $this->htmlElemList['form'] )
			$html .= $this->Html->{'/form'}();

		return $html;
	}
}
?>