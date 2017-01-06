<?
namespace Library;

/**
* read or generate Csv file
*/
class Csv{

	use \Library\Component\Singleton;

	/**
	* read csv file to array
	* @param String $file
	* @return Array
	*/
	function readCsv($file, $useHeaderColumns = true){

		$handle = @fopen($file, "r"); // Open file form read.

		$sep = ',';
		$data1 = fgetcsv($handle, 1000, ";");
		$data2 = fgetcsv($handle, 1000, ",");
		if( count($data1) > count($data2) )
			$sep = ';';

		fclose($handle);

		$handle = @fopen($file, "r"); // Open file form read.
		$i = 1;

		while (($data = fgetcsv($handle, 1000, $sep)) !== FALSE) {

			if( $useHeaderColumns == true ){

				if( $i == 1 ){

					$header = $data;
				}
				if( $i > 1 ){

					foreach($header as $key => $value){

						$tmp[utf8_encode(str_replace(' ', '_', trim($value)))] = $data[$key];
					}
					$csv[] = $tmp;
				}
			}
			else{

				$csv[] = $data;
			}
			$i++;
		}
		fclose($handle);
		return $csv;
	}

	/**
	* make csv file
	* @param Array $data=array() - array(array('key' => 'value'))
	* @param String $file='' - fileName what to create, if not then returns the content
	*/
	function createCsv($data=array(), $file=''){

		$ret = '';
		$i=1;

		foreach($data as $v){

			$row = '';

			if($i == 1){

				foreach($v as $k1 => $v1){

					$row .= $row ? ';'.$k1 : $k1;
				}
				$ret .= $row."\n";
				$row = '';
			}
			foreach($v as $k1 => $v1){

				$row .= $row ? ';'.$v1 : $v1;
			}
			$ret .= $row."\n";
			$i++;
		}
		if($file){

			file_put_contents($file, utf8_decode($ret));
		}
		return $ret;
	}
}
?>