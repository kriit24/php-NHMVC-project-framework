<?
namespace Package;

class Excel{

	public static function Writer($fileName = null, $header = array(), $rows, $downloadFile = false){

		\Autoload::unregister();

		include_once __DIR__ . '/PHPExcel.php';
		include_once __DIR__ . '/PHPExcel/Writer/Excel2007.php';
		include_once __DIR__ . '/PHPExcel/IOFactory.php';

		$objPHPExcel = new \PHPExcel();

		$objPHPExcel->setActiveSheetIndex(0);
		$letters = array();
		if( empty($header) ){

			$header = array_reduce(array_keys($rows[0]), function ($result, $item) {$result[$item] = $item;return $result;}, array());
		}
		$count = count($header);

		for($char = "A"; $char <= "Z"; $char++){

			$letters[] = $char;
			if( count($letters) > $count )
				break;
		}

		$i = 0;
		foreach($header as $key => $value){

			$objPHPExcel->getActiveSheet()->SetCellValue($letters[$i].'1', $value);
			$i++;
		}

		$j = 2;
		foreach($rows as $k => $row){

			$row = array_values($row);

			$i = 0;
			foreach($header as $key => $value){

				$objPHPExcel->getActiveSheet()->SetCellValue($letters[$i] . $j, $row[$key]);
				$i++;
			}
			$j++;
		}

		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	    $randFileName = substr(str_shuffle($chars), 0, 10);
		$fileName = $fileName ? $fileName : $randFileName;

		if( !is_dir(_DIR . '/tmp/xls/') )
			mkdir(_DIR . '/tmp/xls/', 0775, true);
		$tmpfname = _DIR . '/tmp/xls/' . $randFileName . '.xlsx';

		$objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save($tmpfname);

		if( $downloadFile ){

			\Library\FileSystem::singleton()->downloadFile($tmpfname, $fileName);
			unset($tmpfname);
			exit;
		}
		return $tmpfname;
	}

	public static function Reader( $file ){

		\Autoload::unregister();

		include_once __DIR__ . '/PHPExcel.php';
		include_once __DIR__ . '/PHPExcel/Writer/Excel2007.php';
		include_once __DIR__ . '/PHPExcel/IOFactory.php';

		$objReader = \PHPExcel_IOFactory::createReader('Excel2007');
		$objReader->setReadDataOnly(false);
		$objPHPExcel = $objReader->load($file);
		$objWorksheet = $objPHPExcel->getActiveSheet();

		$highestRow = $objWorksheet->getHighestRow();
		$highestColumn = $objWorksheet->getHighestColumn();
		$highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
		$rows = array();
		$data = array();
		$header = array();
		for ($row = 1; $row <= $highestRow; ++$row) {

			for ($col = 0; $col <= $highestColumnIndex; ++$col) {

				$value = trim($objWorksheet->getCellByColumnAndRow($col, $row)->getValue());

				$columnLetter = \PHPExcel_Cell::stringFromColumnIndex($col);
				$cell = $objPHPExcel->getActiveSheet()->getCell($columnLetter . $row);

				if( $row == 1 ){

					$value = strtolower($value);
					$value = str_replace('ä', 'a', $value);
					$value = str_replace('õ', 'o', $value);
					$value = str_replace('ü', 'u', $value);
					$value = str_replace('ö', 'o', $value);

					$header[$col] = str_replace(' ', '_', $value);
				}
				if( $row > 1 && $header[$col] ){

					if(\PHPExcel_Shared_Date::isDateTime($cell)) {

						$InvDate = $cell->getValue();
						$value = date('Y-m-d', \PHPExcel_Shared_Date::ExcelToPHP($InvDate)); 
					}

					$data[$row][$header[$col]] = $value;
				}
			}
		}

		\Autoload::register();
		return $data;
	}
}

?>