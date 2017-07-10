<?
namespace Model\Translate;

class Controller extends \Library{

	public function editData(){

		$lang = $_GET['byLanguage'] ? $_GET['byLanguage'] : _LANG;

		$this->translate->Select()
			->column("*, value AS `".$lang."`")
			->id($_GET['id'])
			->complete(array($this, 'othersByLanguage'))
			->complete(array($this, 'htmlspecialchars'));
		return $this;
	}

	public function othersByLanguage($row){

		$this->translate->Select()
			->where("name = ? AND language != ?",  $row);
		while($row1 = $this->translate->fetch()){

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

		$this->translate->Select()
			->column("*, value AS value2, model AS model2")
			->where("language = '".$lang."'")
			->filter(array(
				'name LIKE %:name%',
				'value LIKE %:value2%',
				'model LIKE %:model2%',
			), $_GET)
			->order("name")
			->paginator(50)
			->complete(array($this, 'htmlspecialchars'));
		return $this;
	}

	public function getTranslateByValue(){

		$translate = urldecode($_GET['translate']);

		$row = $this->translate->Select()
			->column( array('name', 'value') )
			->where( array('value' => $translate, 'language' => _LANG) )
			->limit(1)
			->fetch();

		if( empty($row) ){

			$this->translate->Insert(array(
				'name' => $translate,
				'value' => $translate,
				'language' => _DLANG,
				'model' => 'From/Javascript'
			));

			if( _LANG != _DLANG ){

				$this->translate->Insert(array(
					'name' => $translate,
					'value' => $translate,
					'language' => _LANG,
					'model' => 'From/Javascript'
				));
			}

			return array(
				'name' => $translate,
				'value' => $translate,
			);
		}

		return $row;
	}
}

?>