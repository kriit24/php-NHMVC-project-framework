<?
namespace Library\Component\Extension;

trait Request{

	private function splitRequest($request){

		preg_match('/([a-zA-Z0-9\__]+)\=([a-zA-Z0-9\__]+)/se', $request, $match);
		return array($match[1], $match[2]);
	}

	/**
	* same as if($_POST['action'] == 'save')
	* 
	* @param String $name
	* 
	* @example $this->constrollerName->POST('save')->method(); //it will execute controller method "save" if $_POST['save'] variable is not null
	* @example $this->constrollerName->POST('action=save')->method(); //it will execute controller method "save" if $_POST['action'] variable is "save"
	*/
	public function POST($name, $by_value = false){

		$this->requestRequired = true;
		$this->requestIsSet = false;
		list($reguestName, $reguestValue) = $this->splitRequest($name);

		if($reguestname && $reguestvalue){

			if($_POST[$reguestName] == $reguestValue){

				$this->method = $reguestValue;
				$this->requestIsSet = true;
			}
			return $this;
		}
		else{

			if($_POST[$name]){

				$this->method = $by_value ? $_POST[$name] : $name;
				$this->requestIsSet = true;
			}
		}
		return $this;
	}

	/**
	* same as if($_GET['action'] == 'save')
	* 
	* @param String $name
	* 
	* @example $this->constrollerName->GET('save')->method(); //it will execute controller method "save" if $_GET['save'] variable is not null
	* @example $this->constrollerName->GET('action=save')->method(); //it will execute controller method "save" if $_GET['action'] variable is "save"
	*/
	public function GET($name, $by_value = true){

		$this->requestRequired = true;
		$this->requestIsSet = false;
		list($reguestName, $reguestValue) = $this->splitRequest($name);

		if($reguestName && $reguestValue){

			if($_GET[$reguestName] == $reguestValue){

				$this->method = $reguestValue;
				$this->requestIsSet = true;
			}
			return $this;
		}
		else{

			if($_GET[$name]){

				$this->method = $by_value ? $_GET[$name] : $name;
				$this->requestIsSet = true;
			}
		}
		return $this;
	}
}
?>