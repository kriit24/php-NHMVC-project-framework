<?
namespace Model\Translate\Action;

abstract class updateLanguage{

	public static function init(){

		$language = new \Table\language;
		$language2 = new \Table\language;
		$language3 = new \Table\language;
		$lang = $_GET['byLanguage'] ? $_GET['byLanguage'] : _LANG;
		$_POST = \Library\Http::POST();
		$redis = new \Library\Redis;
		if( $redis->isConnected() )
			$redis->delete('language');

		foreach(\Conf\Conf::LANGUAGE as $v){

			if( $_POST[$v] ){

				//if current language then only update
				if( $v == $lang ){

					$where = "name = '".$_POST['name']."' AND model = '".$_POST['model']."' AND language = '".$v."' ";
					if( $_POST['update_all_with_same_name'] )
						$where = "name = '".$_POST['name']."' AND language = '".$v."' ";

					$language->Select()
						->where($where);
					while($row = $language->fetch()){

						$language2->Update(array('value' => stripslashes($_POST[$v])), array('id' => $row['id']));
					}
				}
				else{

					//select from default language
					$where = "name = '".$_POST['name']."' AND model = '".$_POST['model']."' AND language = '".$lang."' ";
					if( $_POST['update_all_with_same_name'] )
						$where = "name = '".$_POST['name']."' AND language = '".$lang."' ";

					$language->Select()
						->where($where);
					while($row = $language->fetch()){

						//select from other languages by name, model and language
						$row2 = $language2->Select()
							->where("name = '".$row['name']."' AND model = '".$row['model']."' AND language = '".$v."' ")
							->fetch();

						//if language not exists then insert
						if( $language2->Numrows() == 0 ){

							$language3->Insert(array('name' => $row['name'], 'value' => stripslashes($_POST[$v]), 'language' => $v, 'model' => $row['model']));
						}
						//if language exists then update
						else{

							$language3->Update(array('value' => stripslashes($_POST[$v])), array('id' => $row2['id']));
						}
					}
				}
			}
		}
	}
}
?>