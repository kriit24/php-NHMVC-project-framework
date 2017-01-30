<?
namespace Library;

/**
* curl functions
*/
class Curl{

	use \Library\Component\Singleton;

	/**
	* send regular curl request
	* @param String $url - http://domain/
	* @param Array $post_data=array() - array('key' => 'value')
	* @param String $login = '' - if .htaccess login "user:password"
	* @param String $in_file='' - full file path "/var/www/file.pdf"
	* @param Int $in_file_size=0 - filesize('/var/www/file.pdf')
	* @return result
	*/
	function sendCurl($url, $post_data=array(), $login = '', $in_file='', $in_file_size=0){

		$ch = curl_init();

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
		if($login){

			//user:password
			curl_setopt($ch, CURLOPT_USERPWD, $login);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		$Result = curl_exec($ch);

		if (curl_errno($ch)) {

			die( curl_error($ch) );
		}
		curl_close($ch);
		return $Result;
	}

	/*
	In some cases regular curl is not possible to execute some post data
	alternative for that is fil_get_contents
	*/

	public function sendContentCurl($url, $post_data=array(), $method = 'POST'){

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
	function sendCurlWindows($url, $post_data = array()){

		$fp = @fsockopen($url, 80, $errno, $errstr, 10);
		if(!$fp) {
            echo "Error $errno: $errstr<br />\n";
            exit;
		}

		$postdata = "amount=$total&ref=xyz";
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
}
?>