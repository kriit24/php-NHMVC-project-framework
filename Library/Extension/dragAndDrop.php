<?
namespace Library\Extension;

/**
* allows table rows queue change
* 
*/
trait dragAndDrop{

	/**
	* on drop action
	* @param String $table - database table_name
	* @param String $parentColumnName = '' - table parent column_name
	*/
	function _onDrop($table, $parentColumnName = ''){

		if(!$_GET['currentData']) return true;

		//$this->pre($_GET);
		$prevDataParent = 0;
		$prevDataQueue = 0;
		$currentDataParent = 0;
		$currentDataQueue = 0;
		$nextDataParent = 0;
		$nextDataQueue = 0;

		if($_GET['prevData']){

			if($_GET['prevData'] == $_GET['parentData'] && $parentColumnName)
				$row[0]['queue'] = 1;
			else
				$row = $this->Sql->query("SELECT * FROM ".$table." WHERE id = ".$_GET['prevData'], true);
			if($parentColumnName)
				$prevDataParent = $row[0][$parentColumnName];
			if($_GET['parentData'])
				$prevDataParent = $_GET['parentData'];
			$prevDataQueue = isset($row[0]['queue']) && !$row[0]['queue'] ? 1 : $row[0]['queue'];
		}
		if($_GET['currentData']){

			if($_GET['prevData'] == $_GET['parentData'] && $parentColumnName)
				$row[0]['queue'] = 1;
			else
				$row = $this->Sql->query("SELECT * FROM ".$table." WHERE id = ".$_GET['currentData'], true);
			if($parentColumnName)
				$currentDataParent = $row[0][$parentColumnName];
			if($_GET['parentData'])
				$currentDataParent = $_GET['parentData'];
			$currentDataQueue = isset($row[0]['queue']) && !$row[0]['queue'] ? 1 : $row[0]['queue'];
		}
		if($_GET['nextData']){

			if($_GET['prevData'] == $_GET['parentData'] && $parentColumnName)
				$row[0]['queue'] = 1;
			else
				$row = $this->Sql->query("SELECT * FROM ".$table." WHERE id = ".$_GET['nextData'], true);
			if($parentColumnName)
				$nextDataParent = $row[0][$parentColumnName];
			if($_GET['parentData'])
				$nextDataParent = $_GET['parentData'];
			$nextDataQueue = isset($row[0]['queue']) && !$row[0]['queue'] ? 1 : $row[0]['queue'];
		}
		//if u but product into empty category
		if($_GET['parentData'] && $parentColumnName && !$_GET['prevData'] && !$_GET['nextData']){

			$row = $this->Sql->query("SELECT * FROM ".$table." WHERE ".$parentColumnName." = ".$_GET['parentData'], true);
			if($parentColumnName)
				$nextDataParent = $_GET['parentData'];
			$nextDataQueue = 1;
		}

		$setIf = 'none';
		if(($currentDataQueue >= $nextDataQueue && $nextDataQueue) || ($parentColumnName && !$nextDataQueue && !$prevDataQueue)){

			$setIf = 'S1';
			$setParentId = 'nextDataParent='.$nextDataParent;
			$setQueue = $nextDataQueue;
		}
		if($currentDataQueue <= $prevDataQueue && $prevDataQueue){

			$setIf = 'S2';
			$setParentId = "prevDataParent=".$prevDataParent;
			$setQueue = $prevDataQueue;
		}
		if($currentDataQueue >= $nextDataQueue && $currentDataQueue > $prevDataQueue && !$nextDataQueue && $prevDataQueue){

			$setIf = 'S3';
			$setParentId = "prevDataParent=".$prevDataParent;
			$setQueue = $prevDataQueue;
		}

		/*
		echo "\nCUR ".$currentDataQueue .' >= NEXT '. $nextDataQueue .' OR CUR '.$currentDataQueue .' <= PREV '. $prevDataQueue;
		echo "\nIFCLAUSEIS=".$setIf."\n";
		echo "setParentId=".$setParentId."\n";
		echo "prevData == parentData=>".$_GET['prevData']." == ".$_GET['parentData']."\n";
		echo "setQueue=".$setQueue."\n";
		echo "updateId=".$_GET['currentData']."\n\n";
		*/

		$sorted = false;

		//ting1
		if(($currentDataQueue >= $nextDataQueue && $nextDataQueue) || ($parentColumnName && !$nextDataQueue && !$prevDataQueue)){//echo s1;

			$setParentId = "";
			$whereParentId = "";
			if($parentColumnName){

				$setParentId = ", ".$parentColumnName ." = ".$nextDataParent;
				$whereParentId = " AND ".$parentColumnName." = ".$nextDataParent;
			}

			$this->Sql->query("UPDATE ".$table." SET queue = queue+1 WHERE queue >= ".$nextDataQueue.$whereParentId);
			$this->Sql->query("UPDATE ".$table." SET queue = ".$nextDataQueue.$setParentId." WHERE id = ".$_GET['currentData']);
			$curDataParent = $nextDataParent;
		}
		//ting2
		if($currentDataQueue <= $prevDataQueue && $prevDataQueue){//echo s2;

			$setParentId = "";
			$whereParentId = "";
			if($parentColumnName){

				$setParentId = ", ".$parentColumnName ." = ".$prevDataParent;
				$whereParentId = " AND ".$parentColumnName." = ".$prevDataParent;
			}

			$this->Sql->query("UPDATE ".$table." SET queue = queue-1 WHERE queue <= ".$prevDataQueue.$whereParentId);
			$this->Sql->query("UPDATE ".$table." SET queue = ".$prevDataQueue.$setParentId." WHERE id = ".$_GET['currentData']);
			$curDataParent = $prevDataParent;
		}
		if($currentDataQueue >= $nextDataQueue && $currentDataQueue > $prevDataQueue && !$nextDataQueue && $prevDataQueue){//echo s3;

			$setParentId = "";
			$whereParentId = "";
			if($parentColumnName){

				$setParentId = ", ".$parentColumnName ." = ".$prevDataParent;
				$whereParentId = " AND ".$parentColumnName." = ".$prevDataParent;
			}

			$this->Sql->query("UPDATE ".$table." SET queue = queue-1 WHERE queue <= ".$prevDataQueue.$whereParentId);
			$this->Sql->query("UPDATE ".$table." SET queue = ".$prevDataQueue.$setParentId." WHERE id = ".$_GET['currentData']);
			$curDataParent = $nextDataParent;
		}
		if(isset($curDataParent) && $curDataParent != $currentDataParent)
			$this->correctQueue($curDataParent, $table, $parentColumnName);
		$this->correctQueue($currentDataParent, $table, $parentColumnName);
		//exit;
	}

	private function correctQueue($parentId = 0, $table, $parentColumnName){

		$e_category_id = !is_numeric($e_category_id) ? 0 : $e_category_id;

		//echo "SELECT * FROM ".$table.($parentColumnName ? " WHERE ".$parentColumnName." = ".$parentId : "")." ORDER BY queue \n\n";

		$rows = $this->Sql->Select()
			->from($table)
			->where( ($parentColumnName ? " WHERE ".$parentColumnName." = ".$parentId : 1) )
			->order('queue')
			->fetchall();

		$i=1;
		if(is_Array($rows)){

			foreach($rows as $v){

				$this->Sql->query("UPDATE ".$table." SET queue = ".$i." WHERE id=".$v['id']);
				$i++;
			}
		}
	}
}

?>