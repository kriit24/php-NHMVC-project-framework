<?

class some{

	function _action(){

		//cloning is required if needed to make subQuery to the same table
		$table2 = clone $this->table;

		$this->table->Select()
			->fetch();
		while($row = $this->table->Assoc()){

			pre($row);

			//cloned table object will not be overwritten
			$table2->Select()
				->where("id != ".$row['id'])
				->fetch();
			while($row2 = $table2->Assoc()){

				pre($row2);
			}
		}
	}
}

?>