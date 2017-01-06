<?
namespace Library\Extension;

trait Validate{

	private function _is_match($value, $match){

		$isValid = false;
		if(substr($match, 0, 1) != '/'){

			if(preg_match('/'.$match.'/i', $value))
				$isValid = true;
		}
		else{

			if(preg_match($match, $value))
				$isValid = true;
		}
		return $isValid;
	}

	private function _is_string($value){

		$isValid = false;
		if(!preg_match('/([0-9_]+)/i', $value))
			$isValid = true;
		return $isValid;
	}

	private function _is_numeric($value){

		$isValid = false;
		if( is_numeric($value) )
			$isValid = true;
		return $isValid;
	}

	private function _is_email($value){

		$isValid = false;
		if(filter_var($value, FILTER_VALIDATE_EMAIL))
			$isValid = true;
		return $isValid;
	}

	private function _is_equal($value1, $value2){

		$isValid = false;
		if($value1 == $value2)
			$isValid = true;
		return $isValid;
	}

	private function _is_set($value){

		$isValid = false;
		if(strlen($value) > 0)
			$isValid = true;
		return $isValid;
	}

	private function _is_password($value, $length){

		if( !$length )
			$length = '5,12';
		if( !preg_match('/\,/i', $length) )
			$length .= ',12';
		return $this->_is_match($value, '/^(?=.*\d)(?=.*[A-Z])[0-9A-Za-z!@#$%]{'.$length.'}$/');
	}

	private function _in_array($value, $array){

		$isValid = false;
		if( in_array($value, $array) )
			$isValid = true;
		return $isValid;
	}

	private function _validator($value, $object){

		return call_user_func_array($object, array($value));
	}

	private function _isValid($data){

		if( !$this->validateData )
			return true;

		$language = new \Library\Language;

		$isValid = true;
		foreach($this->validateData as $v){

			if( method_exists($this, $v['method']) ){

				if( $v['args'][0] && $v['args'][1] ){

					//$key = $v['args'][0] . '_' . $v['args'][1];
					$key = $v['args'][0];
					$keyName = $v['args'][0];
					$keyName1 = $v['args'][1];
					if( is_string($v['args'][1]) )
						$array = array( $data[ $v['args'][0] ], $data[ $v['args'][1] ], $v['args'][1] );
					else
						$array = array( $data[ $v['args'][0] ], $v['args'][1] );
				}
				else{

					$key = $v['args'][0];
					$keyName = $v['args'][0];
					$keyName1 = null;
					$array = array( $data[ $v['args'][0] ], $v['args'][1] );
				}
				if( is_array($array[0]) ){

					$tmpArray = $array;
					foreach($tmpArray[0] as $v1){

						$array[0] = $v1;
						$valid = call_user_func_array(array($this, $v['method']), $array);
					}
				}
				else
					$valid = call_user_func_array(array($this, $v['method']), $array);
			}

			if( !$valid ){
				
				$content = $this->validMessage[$key][ substr($v['method'], 1) ];
				if( $content )
					new \Library\Component\Error($key, $language->Language($content), true);
				else
					new \Library\Component\Error($key, $key, true);
				$isValid = $valid;
			}
		}
		return $isValid;
	}
}

?>