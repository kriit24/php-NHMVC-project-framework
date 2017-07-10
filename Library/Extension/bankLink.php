<?
namespace library\lib;

trait bankLink{

	function _data($ret_data) {

		$bank = $this->bank;
		$service_code = $this->service;
		$amount = $this->amount;
		$reference = $this->reference;
		$stamp = $this->stamp;

		if( $bank != 'nordea' ){

			return $this->returnForm(array_merge($ret_data, $this->SendPankData($ret_data['privkey'], $ret_data['VK_ACC'], $ret_data['VK_SND_ID'], $reference, $this->description, $amount, $ret_data['VK_NAME'], $stamp, $service_code, $ret_data['VK_RETURN'], $ret_data['VK_CANCEL'], '')));
		} 
		else {

			return $this->returnForm($ret_data);
		}
	}

	private function SendPankData($FILE, $ACC, $SND_ID, $REFERENCE, $MSG, $SUM, $NAME, $STAMP, $VK_SERVICE, $VK_RETURN='', $VK_CANCEL='', $PASSW=''){

		$ret_data = array();

		if($VK_SERVICE == '1001' || $VK_SERVICE == '1002'){

			$ret_data = $this -> PankKaupmees($ACC, $SND_ID, $REFERENCE, $MSG, $SUM, $NAME, $STAMP, $VK_SERVICE, $VK_RETURN, $VK_CANCEL);
		}
		if($VK_SERVICE == '1011' || $VK_SERVICE == '1012'){

			$ret_data = $this -> PankKaupmees($ACC, $SND_ID, $REFERENCE, $MSG, $SUM, $NAME, $STAMP, $VK_SERVICE, $VK_RETURN, $VK_CANCEL);
		}
		if($VK_SERVICE == '4001'){

			$ret_data = $this -> PankAudent($SND_ID, $VK_RETURN, 4001);
		}
		if($VK_SERVICE == '4011'){

			$ret_data = $this -> PankAudent($SND_ID, $VK_RETURN, 4011);
		}
		
		if(is_file($FILE)){

			$fp = fopen($FILE, "r");
			if ( !$fp ) {
				die ("no PEM");
			}

			$priv_key = fread($fp, 8192);
			fclose($fp);

			$pass = $PASSW;
			$pk = openssl_pkey_get_private ($priv_key, $pass);
			if ( !@openssl_sign($ret_data['data'], $sign, $pk) ) {

				die ("Signeerimisel toimus viga.");
			}
			$ret_data['VK_MAC'] = base64_encode($sign);
			openssl_free_key($pk);
		}
		return $ret_data;
	}

	private function NordeaGenMAC($data, $MACKey) {

		$strMac = "";
		foreach ((array)$data as $key=>$item) $strMac .= str_replace('&','&',$item) . '&';
		$strMac .= $MACKey . '&';
		$strMac = strtoupper(md5($strMac));
		return $strMac;
   }

	private function PankAudent($SND_ID, $VK_RETURN, $SERVICE){

		if($SERVICE == 4001){

			$ret_data['VK_SERVICE']="4001";
			$ret_data['VK_VERSION']="008";
			$ret_data['VK_SND_ID']=$SND_ID;
			$ret_data['VK_REPLY']="3002";
			$ret_data['VK_RETURN']=$VK_RETURN;
			$ret_data['VK_DATE']=date("d.m.Y");
			$ret_data['VK_TIME']=date("H:i:s");


			$ret_data['data'] = substr("000".strlen($ret_data['VK_SERVICE']),-3).$ret_data['VK_SERVICE']
			.substr("000".strlen($ret_data['VK_VERSION']),-3).$ret_data['VK_VERSION']
			.substr("000".strlen($ret_data['VK_SND_ID']),-3).$ret_data['VK_SND_ID']
			.substr("000".strlen($ret_data['VK_REPLY']),-3).$ret_data['VK_REPLY']
			.substr("000".strlen($ret_data['VK_RETURN']),-3).$ret_data['VK_RETURN']
			.substr("000".strlen($ret_data['VK_DATE']),-3).$ret_data['VK_DATE']
			.substr("000".strlen($ret_data['VK_TIME']),-3).$ret_data['VK_TIME'];
		}
		if($SERVICE == 4011){

			$ret_data['VK_SERVICE']="4011";
			$ret_data['VK_VERSION']="008";
			$ret_data['VK_SND_ID']=$SND_ID;
			$ret_data['VK_REPLY']="3012";
			$ret_data['VK_RETURN']=$VK_RETURN;
			$ret_data['VK_DATETIME']=date(DATE_ISO8601, strtotime('now'));
			$ret_data['VK_RID']='';


			$ret_data['data'] = substr("000".strlen($ret_data['VK_SERVICE']),-3).$ret_data['VK_SERVICE']
			.substr("000".strlen($ret_data['VK_VERSION']),-3).$ret_data['VK_VERSION']
			.substr("000".strlen($ret_data['VK_SND_ID']),-3).$ret_data['VK_SND_ID']
			.substr("000".strlen($ret_data['VK_REPLY']),-3).$ret_data['VK_REPLY']
			.substr("000".strlen($ret_data['VK_RETURN']),-3).$ret_data['VK_RETURN']
			.substr("000".strlen($ret_data['VK_DATETIME']),-3).$ret_data['VK_DATETIME']
			.substr("000".strlen($ret_data['VK_RID']),-3).$ret_data['VK_RID'];
		}
		return $ret_data;
	}

	private function PankKaupmees($ACC, $SND_ID, $REFERENCE, $MSG, $SUM, $NAME, $STAMP, $SERVICE, $VK_RETURN='', $VK_CANCEL=''){

		//print 'siin';

		$ret_data['VK_SERVICE']		= $SERVICE;
		$ret_data['VK_VERSION']		= "008";
		$ret_data['VK_SND_ID']		= $SND_ID;
		$ret_data['VK_STAMP']		= $STAMP;
		$ret_data['VK_AMOUNT']		= $SUM;
		$ret_data['VK_REPLY']		= "3002";
		$ret_data['VK_CURR']		= "EUR";
		$ret_data['VK_ACC']			= $ACC;
		$ret_data['VK_NAME']		= $NAME;
		$ret_data['VK_REF']			= $REFERENCE;
		$ret_data['VK_MSG']			= $MSG;
		$ret_data['VK_RETURN']		= $VK_RETURN;
		$ret_data['VK_CANCEL']		= $VK_CANCEL;
		$ret_data['VK_LANG']		= $LANG;
		$ret_data['VK_DATE']		= date("d.m.Y");
		$ret_data['VK_TIME']		= date("H:i:s");
		$ret_data['VK_DATETIME']=date(DATE_ISO8601, strtotime('now'));
		//print '<pre>';
		//print_r($ret_data);
		//exit;

		if( $SERVICE == 1001 || $SERVICE == 1002 ){

			$ret_data['data'] = substr("000".strlen($ret_data['VK_SERVICE']),-3).$ret_data['VK_SERVICE']
			.substr("000".strlen($ret_data['VK_VERSION']),-3).$ret_data['VK_VERSION']
			.substr("000".strlen($ret_data['VK_SND_ID']),-3).$ret_data['VK_SND_ID']
			.substr("000".strlen($ret_data['VK_STAMP']),-3).$ret_data['VK_STAMP']
			.substr("000".strlen($ret_data['VK_AMOUNT']),-3).$ret_data['VK_AMOUNT']
			.substr("000".strlen($ret_data['VK_CURR']),-3).$ret_data['VK_CURR']
			.substr("000".strlen($ret_data['VK_ACC']),-3).$ret_data['VK_ACC']
			.substr("000".strlen($ret_data['VK_NAME']),-3).$ret_data['VK_NAME']
			.substr("000".strlen($ret_data['VK_REF']),-3).$ret_data['VK_REF']
			.substr("000".strlen($ret_data['VK_MSG']),-3).$ret_data['VK_MSG'];
		}
		if( $SERVICE == 1011 || $SERVICE == 1012 ){

			$ret_data['data'] = substr("000".strlen($ret_data['VK_SERVICE']),-3).$ret_data['VK_SERVICE']
			.substr("000".strlen($ret_data['VK_VERSION']),-3).$ret_data['VK_VERSION']
			.substr("000".strlen($ret_data['VK_SND_ID']),-3).$ret_data['VK_SND_ID']
			.substr("000".strlen($ret_data['VK_STAMP']),-3).$ret_data['VK_STAMP']
			.substr("000".strlen($ret_data['VK_AMOUNT']),-3).$ret_data['VK_AMOUNT']
			.substr("000".strlen($ret_data['VK_CURR']),-3).$ret_data['VK_CURR']
			.substr("000".strlen($ret_data['VK_REF']),-3).$ret_data['VK_REF']
			.substr("000".strlen($ret_data['VK_MSG']),-3).$ret_data['VK_MSG']
			.substr("000".strlen($ret_data['VK_RETURN']),-3).$ret_data['VK_RETURN']
			.substr("000".strlen($ret_data['VK_CANCEL']),-3).$ret_data['VK_CANCEL']
			.substr("000".strlen($ret_data['VK_DATETIME']),-3).$ret_data['VK_DATETIME'];
		}

		return $ret_data;
	}
	
	function _check() {

		$result = false;
		if ($_POST['VK_SERVICE']){

			if ($_POST['VK_SERVICE']=='1101'){ 

				$data = substr("000".strlen($_POST['VK_SERVICE']),-3).$_POST['VK_SERVICE']
				.substr("000".strlen($_POST['VK_VERSION']),-3).$_POST['VK_VERSION']
				.substr("000".strlen($_POST['VK_SND_ID']),-3).$_POST['VK_SND_ID']
				.substr("000".strlen($_POST['VK_REC_ID']),-3).$_POST['VK_REC_ID']
				.substr("000".strlen($_POST['VK_STAMP']),-3).$_POST['VK_STAMP']
				.substr("000".strlen($_POST['VK_T_NO']),-3).$_POST['VK_T_NO']
				.substr("000".strlen($_POST['VK_AMOUNT']),-3).$_POST['VK_AMOUNT']
				.substr("000".strlen($_POST['VK_CURR']),-3).$_POST['VK_CURR']
				.substr("000".strlen($_POST['VK_REC_ACC']),-3).$_POST['VK_REC_ACC']
				.substr("000".strlen($_POST['VK_REC_NAME']),-3).$_POST['VK_REC_NAME']
				.substr("000".strlen($_POST['VK_SND_ACC']),-3).$_POST['VK_SND_ACC']
				.substr("000".strlen($_POST['VK_SND_NAME']),-3).$_POST['VK_SND_NAME']
				.substr("000".strlen($_POST['VK_REF']),-3).$_POST['VK_REF']
				.substr("000".strlen($_POST['VK_MSG']),-3).$_POST['VK_MSG']
				.substr("000".strlen($_POST['VK_T_DATE']),-3).$_POST['VK_T_DATE'];
			}
			else if ($_POST['VK_SERVICE']=='1111'){ 

				$data = str_pad (mb_strlen($_POST["VK_SERVICE"], "UTF-8"),   3, "0", STR_PAD_LEFT) . $_POST["VK_SERVICE"].
				str_pad (mb_strlen($_POST["VK_VERSION"], "UTF-8"),   3, "0", STR_PAD_LEFT) . $_POST["VK_VERSION"].
				str_pad (mb_strlen($_POST["VK_SND_ID"], "UTF-8"),    3, "0", STR_PAD_LEFT) . $_POST["VK_SND_ID"].
				str_pad (mb_strlen($_POST["VK_REC_ID"], "UTF-8"),    3, "0", STR_PAD_LEFT) . $_POST["VK_REC_ID"].
				str_pad (mb_strlen($_POST["VK_STAMP"], "UTF-8"),     3, "0", STR_PAD_LEFT) . $_POST["VK_STAMP"].
				str_pad (mb_strlen($_POST["VK_T_NO"], "UTF-8"),      3, "0", STR_PAD_LEFT) . $_POST["VK_T_NO"].
				str_pad (mb_strlen($_POST["VK_AMOUNT"], "UTF-8"),    3, "0", STR_PAD_LEFT) . $_POST["VK_AMOUNT"].
				str_pad (mb_strlen($_POST["VK_CURR"], "UTF-8"),      3, "0", STR_PAD_LEFT) . $_POST["VK_CURR"].
				str_pad (mb_strlen($_POST["VK_REC_ACC"], "UTF-8"),   3, "0", STR_PAD_LEFT) . $_POST["VK_REC_ACC"].
				str_pad (mb_strlen($_POST["VK_REC_NAME"], "UTF-8"),  3, "0", STR_PAD_LEFT) . $_POST["VK_REC_NAME"].
				str_pad (mb_strlen($_POST["VK_SND_ACC"], "UTF-8"),   3, "0", STR_PAD_LEFT) . $_POST["VK_SND_ACC"].
				str_pad (mb_strlen($_POST["VK_SND_NAME"], "UTF-8"),  3, "0", STR_PAD_LEFT) . $_POST["VK_SND_NAME"].
				str_pad (mb_strlen($_POST["VK_REF"], "UTF-8"),       3, "0", STR_PAD_LEFT) . $_POST["VK_REF"].
				str_pad (mb_strlen($_POST["VK_MSG"], "UTF-8"),       3, "0", STR_PAD_LEFT) . $_POST["VK_MSG"].
				str_pad (mb_strlen($_POST["VK_T_DATETIME"], "UTF-8"), 3, "0", STR_PAD_LEFT) . $_POST["VK_T_DATETIME"];
			}
			else if ($_POST['VK_SERVICE'] == '1901'){
				//payment failure

				$data = substr("000".strlen($_POST['VK_SERVICE']),-3).$_POST['VK_SERVICE']
				.substr("000".strlen($_POST['VK_VERSION']),-3).$_POST['VK_VERSION']
				.substr("000".strlen($_POST['VK_SND_ID']),-3).$_POST['VK_SND_ID']
				.substr("000".strlen($_POST['VK_REC_ID']),-3).$_POST['VK_REC_ID']
				.substr("000".strlen($_POST['VK_STAMP']),-3).$_POST['VK_STAMP']
				.substr("000".strlen($_POST['VK_REF']),-3).$_POST['VK_REF']
				.substr("000".strlen($_POST['VK_MSG']),-3).$_POST['VK_MSG'];
			}
			else if ($_POST['VK_SERVICE'] == '3002'){
				/*person data from bank*/
				$data = substr("000".strlen($_POST['VK_SERVICE']),-3).$_POST['VK_SERVICE']
				.substr("000".strlen($_POST['VK_VERSION']),-3).$_POST['VK_VERSION']
				.substr("000".strlen($_POST['VK_USER']),-3).$_POST['VK_USER']
				.substr("000".strlen($_POST['VK_DATE']),-3).$_POST['VK_DATE']
				.substr("000".strlen($_POST['VK_TIME']),-3).$_POST['VK_TIME']
				.substr("000".strlen($_POST['VK_SND_ID']),-3).$_POST['VK_SND_ID']
				.substr("000".strlen($_POST['VK_INFO']),-3).$_POST['VK_INFO'];

				preg_match('/ISIK\:([0-9_]+)\;/s', $_POST['VK_INFO'], $match);
				$_POST['PERSONAL_CODE'] = $match[1];
				preg_match('/NIMI:([a-zA-Z0-9\,[:space:]_]+)/s', $_POST['VK_INFO'], $match);
				$_POST['NAME'] = $match[1];
			}
			else if ($_POST['VK_SERVICE'] == '3012'){

				$data = str_pad (mb_strlen($_POST["VK_SERVICE"], "UTF-8"),   3, "0", STR_PAD_LEFT) . $_POST["VK_SERVICE"].
				str_pad (mb_strlen($_POST["VK_VERSION"], "UTF-8"),   3, "0", STR_PAD_LEFT) . $_POST["VK_VERSION"].
				str_pad (mb_strlen($_POST["VK_USER"], "UTF-8"),    3, "0", STR_PAD_LEFT) . $_POST["VK_USER"].
				str_pad (mb_strlen($_POST["VK_DATETIME"], "UTF-8"),    3, "0", STR_PAD_LEFT) . $_POST["VK_DATETIME"].
				str_pad (mb_strlen($_POST["VK_SND_ID"], "UTF-8"),      3, "0", STR_PAD_LEFT) . $_POST["VK_SND_ID"].
				str_pad (mb_strlen($_POST["VK_REC_ID"], "UTF-8"),   3, "0", STR_PAD_LEFT) . $_POST["VK_REC_ID"].
				str_pad (mb_strlen($_POST["VK_USER_NAME"], "UTF-8"),  3, "0", STR_PAD_LEFT) . $_POST["VK_USER_NAME"].
				str_pad (mb_strlen($_POST["VK_USER_ID"], "UTF-8"),   3, "0", STR_PAD_LEFT) . $_POST["VK_USER_ID"].
				str_pad (mb_strlen($_POST["VK_COUNTRY"], "UTF-8"),  3, "0", STR_PAD_LEFT) . $_POST["VK_COUNTRY"].
				str_pad (mb_strlen($_POST["VK_OTHER"], "UTF-8"),       3, "0", STR_PAD_LEFT) . $_POST["VK_OTHER"].
				str_pad (mb_strlen($_POST["VK_TOKEN"], "UTF-8"),       3, "0", STR_PAD_LEFT) . $_POST["VK_TOKEN"].
				str_pad (mb_strlen($_POST["VK_RID"], "UTF-8"), 3, "0", STR_PAD_LEFT) . $_POST["VK_RID"];
			}

			if( $_POST['VK_SND_ID'] == 'HP' ) {

				$FILE = $this->swedPublic;
				//$FILE='swed_priv.pem';
			}
			if ( $_POST['VK_SND_ID'] == 'LHV' ){

				$FILE = $this->lhvPublic;
			}
			if ( $_POST['VK_SND_ID'] == 'EYP' ){

				$FILE = $this->sebPublic;
			}
			if ( $_POST['VK_SND_ID'] == 'SAMPOPANK' ){

				$FILE = $this->danskePublic;
			}
			if(is_file($FILE)){

				$signature = base64_decode($_POST['VK_MAC']);
				$pubkeyid = openssl_get_publickey(file_get_contents($FILE));
				$result = openssl_verify($data, $signature, $pubkeyid);
				openssl_free_key($pubkeyid);
			} 
		}
		if ( $_REQUEST["SOLOPMT_RETURN_MAC"] ){

			$hash = $_REQUEST['SOLOPMT_RETURN_VERSION'].'&'.$_REQUEST['SOLOPMT_RETURN_STAMP'].'&'.$_REQUEST['SOLOPMT_RETURN_REF'].'&'.$_REQUEST['SOLOPMT_RETURN_PAID'].'&'.$this->nordeaKey.'&';      
			$hash = strtoupper(md5(($hash)));
			if ($hash == $_REQUEST['SOLOPMT_RETURN_MAC']) {

					$result = true;
					$_POST['VK_STAMP'] = substr($_REQUEST['SOLOPMT_RETURN_STAMP'],0,strlen($_REQUEST['SOLOPMT_RETURN_STAMP'])-12);//substr($_REQUEST['SOLOPMT_RETURN_STAMP']);
			}
		}
		return $result;
	}

	function returnForm($data){

		$imgName = $this->bank.'Img';

		if( $data['VK_SERVICE'] == 1001 ){

			return '<form name="up" action="'.$data['VK_URL'].'" method="POST" class="bank-form">
				<input type="hidden" name="VK_SERVICE" value="'.$data['VK_SERVICE'].'">
				<input type="hidden" name="VK_VERSION" value="'.$data['VK_VERSION'].'">
				<input type="hidden" name="VK_SND_ID" value="'.$data['VK_SND_ID'].'">
				<input type="hidden" name="VK_STAMP" value="'.$data['VK_STAMP'].'">
				<input type="hidden" name="VK_AMOUNT" value="'.$data['VK_AMOUNT'].'">
				<input type="hidden" name="VK_CURR" value="'.$data['VK_CURR'].'">
				<input type="hidden" name="VK_ACC" value="'.$data['VK_ACC'].'">
				<input type="hidden" name="VK_NAME" value="'.$data['VK_NAME'].'">
				<input type="hidden" name="VK_REF" value="'.$data['VK_REF'].'">
				<input type="hidden" name="VK_MSG" value="'.$data['VK_MSG'].'">
				<input type="hidden" name="VK_MAC" value="'.$data['VK_MAC'].'">
				<input type="hidden" name="VK_RETURN" value="'.$data['VK_RETURN'].'">
				<input type="hidden" name="VK_LANG" value="EST">
				<input type="hidden" name="VK_CHARSET" value="UTF-8">
				<img src="'.$this->$imgName.'" align="left" class="bank-logo">
				<input type="submit" value="'._tr('Maksma').'" class="bank-button">
			</form>';
		}
		if( $data['VK_SERVICE'] == 1012 ){

			return '<form name="up" action="'.$data['VK_URL'].'" method="POST" class="bank-form">
				<input type="hidden" name="VK_SERVICE" value="'.$data['VK_SERVICE'].'">
				<input type="hidden" name="VK_VERSION" value="'.$data['VK_VERSION'].'">
				<input type="hidden" name="VK_SND_ID" value="'.$data['VK_SND_ID'].'">
				<input type="hidden" name="VK_STAMP" value="'.$data['VK_STAMP'].'">
				<input type="hidden" name="VK_AMOUNT" value="'.$data['VK_AMOUNT'].'">
				<input type="hidden" name="VK_CURR" value="'.$data['VK_CURR'].'">
				<input type="hidden" name="VK_REF" value="'.$data['VK_REF'].'">
				<input type="hidden" name="VK_MSG" value="'.$data['VK_MSG'].'">
				<input type="hidden" name="VK_MAC" value="'.$data['VK_MAC'].'">
				<input type="hidden" name="VK_RETURN" value="'.$data['VK_RETURN'].'">
				<input type="hidden" name="VK_CANCEL" value="'.$data['VK_CANCEL'].'">
				<input type="hidden" name="VK_DATETIME" value="'.$data['VK_DATETIME'].'">
				<input type="hidden" name="VK_LANG" value="EST">
				<input type="hidden" name="VK_ENCODING" value="UTF-8">
				<img src="'.$this->$imgName.'" align="left" class="bank-logo">
				<input type="submit" value="'._tr('Maksma').'" class="bank-button">
			</form>';
		}
		if( $data['VK_SERVICE'] == 4001 ){

			return '<form name="up" action="'.$data['VK_URL'].'" method="POST" class="bank-form">
				<input type="hidden" name="VK_SERVICE" value="'.$data['VK_SERVICE'].'">
				<input type="hidden" name="VK_VERSION" value="'.$data['VK_VERSION'].'">
				<input type="hidden" name="VK_SND_ID" value="'.$data['VK_SND_ID'].'">
				<input type="hidden" name="VK_REPLY" value="'.$data['VK_REPLY'].'">
				<input type="hidden" name="VK_RETURN" value="'.$data['VK_RETURN'].'">
				<input type="hidden" name="VK_DATE" value="'.$data['VK_DATE'].'">
				<input type="hidden" name="VK_TIME" value="'.$data['VK_TIME'].'">
				<input type="hidden" name="VK_CHARSET" value="UTF-8">
				<input type="hidden" name="VK_MAC" value="'.$data['VK_MAC'].'">
				<img src="'.$this->$imgName.'" align="left" class="bank-logo">
				<input type="submit" value="'._tr('Tuvastama').'" class="bank-button">
			</form>';
		}
		if( $data['VK_SERVICE'] == 4011 ){

			return '<form name="up" action="'.$data['VK_URL'].'" method="POST" class="bank-form">
				<input type="hidden" name="VK_SERVICE" value="'.$data['VK_SERVICE'].'">
				<input type="hidden" name="VK_VERSION" value="'.$data['VK_VERSION'].'">
				<input type="hidden" name="VK_SND_ID" value="'.$data['VK_SND_ID'].'">
				<input type="hidden" name="VK_REPLY" value="'.$data['VK_REPLY'].'">
				<input type="hidden" name="VK_RETURN" value="'.$data['VK_RETURN'].'">
				<input type="hidden" name="VK_DATETIME" value="'.$data['VK_DATETIME'].'">
				<input type="hidden" name="VK_RID" value="'.$data['VK_RID'].'">
				<input type="hidden" name="VK_ENCODING" value="UTF-8">
				<input type="hidden" name="VK_MAC" value="'.$data['VK_MAC'].'">
				<img src="'.$this->$imgName.'" align="left" class="bank-logo">
				<input type="submit" value="'._tr('Tuvastama').'" class="bank-button">
			</form>';
		}
	}

	function __call($method, $variables){

		$this->$method = $variables[0];
	}
}


?>