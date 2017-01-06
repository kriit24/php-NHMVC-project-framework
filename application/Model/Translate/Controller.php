<?
namespace Model\Translate;

class Controller extends \Library{

	public function editData(){

		$lang = $_GET['byLanguage'] ? $_GET['byLanguage'] : _LANG;

		$this->language->Select()
			->column("*, value AS `".$lang."`")
			->id($_GET['id'])
			->method(array($this, 'othersByLanguage'))
			->method(array($this, 'htmlspecialchars'));
		return $this;
	}

	public function othersByLanguage($row){

		$this->language->Select()
			->where("name = ? AND language != ?",  $row);
		while($row1 = $this->language->fetch()){

			$row[ $row1['language'] ] = $row1['value'];
		}
		return $row;
	}

	public function htmlspecialchars( $row ){

		$row['clear_name'] = strip_tags($row['name']);
		$row['name'] = htmlspecialchars($row['name']);
		$row['clear_value'] = strip_tags($row['value']);
		return $row;
	}

	public function translateData(){

		$lang = $_GET['byLanguage'] ? $_GET['byLanguage'] : _LANG;

		$this->language->Select()
			->column("*, value AS value2, model AS model2")
			->where("language = '".$lang."'")
			->filter(array(
				'name LIKE %:name%',
				'value LIKE %:value2%',
				'model LIKE %:model2%',
			), $_GET)
			->order("name")
			->paginator(50)
			->method(array($this, 'htmlspecialchars'));
		return $this;
	}
}

?>