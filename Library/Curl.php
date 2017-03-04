<?
namespace Library;

/**
* curl functions
*/
class Curl{

	private static $ch;
	private static $cookie;

	/**
	* send regular curl request
	* @param String $url - http://domain/
	* @param Array $post_data=array() - array('key' => 'value')
	* @param String $login = '' - if .htaccess login "user:password"
	* @param String $in_file='' - full file path "/var/www/file.pdf"
	* @param Int $in_file_size=0 - filesize('/var/www/file.pdf')
	* @return result
	*/
	public static function get($url, $sshLogin = ''){

		register_shutdown_function(array(new self(), 'close'));

		return self::makeRequest($url, array(), $sshLogin);
	}

	public static function post($url, $post_data, $sshLogin = '', $in_file='', $in_file_size=0){

		register_shutdown_function(array(new self(), 'close'));

		return self::makeRequest($url, $post_data, $sshLogin, $in_file, $in_file_size);
	}

	private static function makeRequest($url, $post_data=array(), $sshLogin = '', $in_file='', $in_file_size=0){

		$ch = self::$ch ? self::$ch : curl_init();
		$cookieFile = uniqid('_cookie', true);

		if( !is_dir(get_include_path() . '/tmp/cookies') )
			mkdir(get_include_path() . '/tmp/cookies');

		curl_setopt($ch, CURLOPT_URL, $url);
		if(is_array($post_data) && count($post_data)>0){

			curl_setopt($ch, CURLOPT_POST, 1 );
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		}
		if($in_file){

			curl_setopt($ch, CURLOPT_PUT, true);
			curl_setopt($ch, CURLOPT_INFILE, $in_file);
			curl_setopt($ch, CURLOPT_INFILESIZE, $in_file_size);
		}
		if($sshLogin){

			//user:password
			curl_setopt($ch, CURLOPT_USERPWD, $sshLogin);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		if( !self::$cookie ){

			curl_setopt($ch, CURLOPT_COOKIESESSION, true);
			curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);  //could be empty, but cause problems on some hosts
			curl_setopt($ch, CURLOPT_COOKIEFILE, get_include_path() . '/tmp/cookies');  //could be empty, but cause problems on some hosts
		}
		$Result = curl_exec($ch);

		if (curl_errno($ch)) {

			die( curl_error($ch) );
		}
		self::$ch = $ch;
		self::$cookie = $cookieFile;
		return $Result;
	}

	/*
	In some cases regular curl is not possible to execute some post data
	alternative for that is fil_get_contents
	*/

	public static function sendContentCurl($url, $post_data=array(), $method = 'POST'){

		$postdata = http_build_query($post_data);

		$opts = array('http' =>
			array(
				'method'  => $method,
				'header'  => 'Content-type: application/x-www-form-urlencoded',
				'content' => $postdata
			)
		);

		$context  = stream_context_create($opts);
		$result = file_get_contents($url, false, $context);
		return $result;
	}

	/**
	* alternate method for curl in some cases if windows server
	*/
	public static function sendCurlWindows($url, $post_data = array()){

		$fp = @fsockopen($url, 80, $errno, $errstr, 10);
		if(!$fp) {
            echo "Error $errno: $errstr<br />\n";
            exit;
		}

		$postdata = "";
		if( !empty($post_data) ){

			foreach($post_data as $k => $v){

				$postdata .= ($postdata ? '&' : '') . $k .'='. $v;
			}
		}
		$postdata = urlencode($postdata);

		$data = "POST /cgi-bin/calc.cgi HTTP/1.0\r\n";
		$data .= "Host: www.mydomain.com\r\n";
		$data .= "Content-type: application/x-www-form-urlencoded\r\n";
		$data .= "Content-length: " . strlen($postdata) . "\r\n";
		$data .= "\r\n";
		$data .= $postdata;
		$data .= "\r\n";

		fputs($fp, $data);

		while(!feof($fp)) {
			$return .= fgets($fp);
		}

		fclose($fp);

		return $return;
	}

	function close(){

		if( gettype(self::$ch) == 'resource' )
			curl_close(self::$ch);
	}

	function destruct(){

		$this->close();
	}
}
?>