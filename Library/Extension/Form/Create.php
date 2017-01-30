<?
namespace Library\Extension\Form;

trait Create{

	private function getHtmlElemAttr($elem, $type, $i = null){

		$ret = array();
		if( $elem[ $type ] )
			$ret = $elem[ $type ];
		if( isset($i) && $elem[ $type . '('.$i.')' ] )
			$ret = $elem[ $type . '('.$i.')' ];
		if( isset($i) && $elem[ $type ][ $i ] )
			$ret = $elem[ $type ][ $i ];

		if( gettype($ret) == 'string' ){

			pre($elem);
			pre($ret);

			die( '<b style="color:red;">Attribute must be array, string given</b>' );
		}
		return $ret;
	}

	private function createForm($attr){

		if( isset($this->elemsType['form']) ){

			$name = $this->elemsType['form'][0];

			list(, $e) = $this->getElement($name);
			if( isset($this->elemsType['file']) )
				$e['attr']['enctype'] = 'multipart/form-data';

			$this->_remove($name);
			return $this->Html->{'form'}($e);
		}
		return false;
	}

	private function createTable($attr){

		$attr['table'] = array_merge(array('class' => $this->tableClass), ($attr['table'] ?? array()));
		$table = $this->addelem('table', '', $this->getHtmlElemAttr($attr, 'table'), true);
		return $this->Html->{'table'}($table);
	}

	private function createHead($attr){

		if( !$this->htmlElemList['label'] )
			return '';
			//return (!$this->htmlElemList['label'] ? $this->Html->{'tbody'}() : '');

		$row = array(
			$this->addelem('thead', '', $this->getHtmlElemAttr($attr, 'thead'), true),
			$this->addelem('tr', '', $this->getHtmlElemAttr($attr, 'tr'), true)
		);

		$i = 0;

		foreach($this->elemList as $elem){

			//$labelValue = $elem['label'] && strip_tags($elem['label']) == $elem['label'] ? $this->Language($elem['label']) : $elem['label'];
			$labelValue = $elem['label'] && strip_tags($elem['label']) == $elem['label'] ? $elem['label'] : $elem['label'];
			$labelAttr = array('value' => ($elem['label'] ? $labelValue : ''), 'attr' => $this->getHtmlElemAttr($elem, 'label-attr', $i));
			if( !empty($attr) && $setLabelAttr = $this->getHtmlElemAttr($this->getHtmlElemAttr($attr, 'tr'), 'th', $i) ){

				$labelAttr['attr'] = !empty($labelAttr) ? array_merge($labelAttr['attr'], $setLabelAttr) : $setLabelAttr;
			}
			$row[] = $this->addelem('th', '', $labelAttr, true);
			$row[] = $this->addelem('/th', '', array(), true);
			$i++;
		}
		$row[] = $this->addelem('/tr', '', array(), true);
		$row[] = $this->addelem('/thead', '', array(), true);
		return $this->createElement($row, array());
	}

	private function createRow($attr, $k){

		$attrTbody = $this->getHtmlElemAttr($attr, 'tbody');

		if( !$this->htmlElemList['tbody'] && $this->htmlElemList['table'] ){

			$row = array(
				$this->addelem('tbody', '', $attrTbody, true),
				$this->addelem('tr', '', $this->getHtmlElemAttr($attrTbody, 'tr', $k), true)
			);
			$this->htmlElemList['tbody'] = true;
		}
		else{

			$row = array($this->addelem('tr', '', $this->getHtmlElemAttr($attrTbody, 'tr', $k), true));
		}

		$i = 0;
		foreach($this->elemList as $key => $elem){
			
			$elemAttr = isset($this->elemAttr[$key][$k]) ? $this->elemAttr[$key][$k] : array();
			$elem['attr'] = array_merge($elem['attr'], $elemAttr);
			$elemHtml = $this->createElement(array($elem));

			$tdAttr = array_merge(array('value' => $elemHtml), $this->getHtmlElemAttr($this->getHtmlElemAttr($attrTbody, 'tr', $k), 'td', $i));
			$row[] = $this->addelem('td', '', $tdAttr, true);
			$row[] = $this->addelem('/td', '', array(), true);
			$i++;
		}
		$row[] = $this->addelem('/tr', '', array(), true);
		return $this->createElement($row, array());
	}

	private function createList($attr){

		$html = '';
		$attrTbody = $this->getHtmlElemAttr($attr, 'tbody');
		if( !$this->htmlElemList['tbody'] && $this->htmlElemList['table'] )
			$html = $this->Html->{'tbody'}($attrTbody);
		$i = 0;
		foreach($this->elemList as $key => $elem){

			$elemAttr = isset($this->elemAttr[$key][0]) ? $this->elemAttr[$key][0] : array();
			$elem['attr'] = array_merge($elem['attr'], $elemAttr);
			$elemHtml = $this->createElement(array($elem));

			$trAttr = $this->getHtmlElemAttr($attrTbody, 'tr', $i);
			//$labelValue = $elem['label'] && strip_tags($elem['label']) == $elem['label'] ? $this->Language($elem['label']) : $elem['label'];
			$labelValue = $elem['label'] && strip_tags($elem['label']) == $elem['label'] ? $elem['label'] : $elem['label'];
			$thAttr = array_merge(array('value' => ($elem['label'] ? $labelValue : '')), $this->getHtmlElemAttr($elem, 'label-attr'));
			$tdAttr = array_merge(array('value' => $elemHtml), $this->getHtmlElemAttr($trAttr, 'td'));

			if( $this->htmlElemList['label'] ){

				$row = array(
					$this->addelem('tr', '', $trAttr, true),
					$this->addelem('th', '', $thAttr, true),
					$this->addelem('/th', '', array(), true),
					$this->addelem('td', '', $tdAttr, true),
					$this->addelem('/td', '', array(), true),
					$this->addelem('/tr', '', array(), true)
				);
			}
			else{

				$row = array(
					$this->addelem('tr', '', $trAttr, true),
					$this->addelem('td', '', $tdAttr, true),
					$this->addelem('/td', '', array(), true),
					$this->addelem('/tr', '', array(), true)
				);
			}
			$html .= $this->createElement($row);
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
		$row = array();
		$list = array();

		if( gettype($error) == 'string' && is_Array(json_decode($error, true)) )
			$error = json_decode($error, true);

		if( !is_array($error) && $error )
			$error = array($error);

		if(is_array($error)){

			$row[] = $this->addelem('div', '', array('class' => 'col error-label', 'style' => 'background:#ffecec;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;display: block;margin-bottom: 5px;padding: 10px 15px;'), true);

			foreach($error as $k => $v){

				$elem .= $elem ? ',[name='.str_replace(' ', '_', $k).']' : '[name='.str_replace(' ', '_', $k).']';
				if($v && !in_array($v, $list)){

					$row[] = $this->addelem('h4', '', array('style' => 'margin:0.5rem;font-size:20px;font-weight:bold;', 'value' => $v), true);
					$row[] = $row[] = $this->addelem('/h4', '', array(), true);
				}
				$list[] = $v;
			}

			$row[] = $this->addelem('/div', '', array(), true);
			//$row[] = $this->addelem('div', '', array('style' => 'clear:both;'), true);
			//$row[] = $this->addelem('/div', '', array(), true);

			if($elem){

				$row[] = $this->addelem('script', '', 'if(onLoad != undefined){onLoad.push( function(e){$("'.$elem.'").addClass("errorLabel");});}else{$(document).ready(function(){$("'.$elem.'").addClass("errorLabel");});}', true);
				$row[] = $this->addelem('/script', '', '', true);
			}
			$html = $this->createElement($row, array());
		}
		return $html;
	}

	/**
	* show message label
	* @param Array $message AS $this->message object
	*/
	private function createMessageLabel($message, $return = false){

		$html = '';
		$row = array();
		$list = array();

		if( gettype($message) == 'string' && is_Array(json_decode($message, true)) )
			$message = json_decode($message, true);

		if( !is_array($message) && $message )
			$message = array($message);

		if(is_array($message)){

			$row[] = $this->addelem('div', '', array('class' => 'col message-label', 'style' => 'background:#ecffec;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;display: block;margin-bottom: 5px;padding: 10px 15px;'), true);

			foreach($message as $k => $v){

				$elem .= $elem ? ',[name='.str_replace(' ', '_', $k).']' : '[name='.str_replace(' ', '_', $k).']';
				if($v && !in_array($v, $list)){

					$row[] = $this->addelem('h4', '', array('style' => 'margin:0.5rem;font-size:20px;font-weight:bold;', 'value' => $v), true);
					$row[] = $row[] = $this->addelem('/h4', '', array(), true);
				}
				$list[] = $v;
			}

			$row[] = $this->addelem('/div', '', array(), true);
			//$row[] = $this->addelem('div', '', array('style' => 'clear:both;'), true);
			//$row[] = $this->addelem('/div', '', array(), true);

			if($elem){

				$row[] = $this->addelem('script', '', 'if(onLoad != undefined){onLoad.push( function(e){$("'.$elem.'").addClass("messageLabel");});}else{$(document).ready(function(){$("'.$elem.'").addClass("messageLabel");});}', true);
				$row[] = $this->addelem('/script', '', '', true);
			}
			$html = $this->createElement($row, array());
		}
		return $html;
	}

	private function createHtmlElements(){

		$html = '';
		$i = 0;
		foreach($this->elemList as $elem){

			$html .= $this->createElement(array($elem));
			$i++;
		}
		return $html;
	}

	private function createElement($row){

		$elemHtml = '';

		foreach($row as $elem){

			$elemClass = $this->getClass($elem['type']);
			$elemHtml .= $elemClass->{$elem['elem']}($elem);
		}
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

				$html .= $this->createList($attr);
			}
			if( $this->bodyType == 'row' ){

				if( !empty($this->data) ){

					$dataArray = $this->data;
					//die(pre($dataArray));
				
					$k = 0;
					foreach($dataArray as $data){

						if( gettype($data) == 'array' ){

							$this->Form->setData($data);
							$this->Html->setData($data);

							$html .= $this->createRow( $attr, $k );
							$k++;
						}
						if( gettype($data) == 'object' ){

							$htmlObject = $data;
							$html .= $htmlObject->createRowFromObject( $attr, $k );
						}
					}
				}
			}
			if( $this->htmlElemList['row'] ){

				foreach($this->htmlElemList['row'] as $htmlObject){

					$html .= $htmlObject->createRowFromObject( $attr, 0 );
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

				$this->Form->setData( $data );
				$this->Html->setData( $data );

				$html .= $this->createRow( $attr, $k );
				$k++;
			}
		}
		else{

			$html .= $this->createRow( $attr, $k );
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