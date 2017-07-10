<?
namespace Model\Translate\Action;

abstract class updateLanguage{

	public static function init(){

		$translate = new \Table\translate;
		$translate2 = new \Table\translate;
		$translate3 = new \Table\translate;
		$lang = $_GET['byLanguage'] ? $_GET['byLanguage'] : _LANG;
		$_POST = \Library\Http::POST();
		$redis = new \Library\Redis;
		if( $redis->isConnected() )
			$redis->delete('translate');

		foreach(\Conf\Conf::LANGUAGE as $v){

			if( $_POST[$v] ){

				//if current language then only update
				if( $v == $lang ){

					$where = "name = '".$_POST['name']."' AND model = '".$_POST['model']."' AND language = '".$v."' ";
					if( $_POST['update_all_with_same_name'] )
						$where = "name = '".$_POST['name']."' AND language = '".$v."' ";

					$translate->Select()
						->where($where);
					while($row = $translate->fetch()){

						$translate2->Update(array('value' => stripslashes($_POST[$v])), array('id' => $row['id']));
					}
				}
				else{

					//select from default language
					$where = "name = '".$_POST['name']."' AND model = '".$_POST['model']."' AND language = '".$lang."' ";
					if( $_POST['update_all_with_same_name'] )
						$where = "name = '".$_POST['name']."' AND language = '".$lang."' ";

					$translate->Select()
						->where($where);
					while($row = $translate->fetch()){

						//select from other languages by name, model and language
						$row2 = $translate2->Select()
							->where("name = '".$row['name']."' AND model = '".$row['model']."' AND language = '".$v."' ")
							->fetch();

						//if language not exists then insert
						if( $translate2->Numrows() == 0 ){

							$translate3->Insert(array('name' => $row['name'], 'value' => stripslashes($_POST[$v]), 'language' => $v, 'model' => $row['model']));
						}
						//if language exists then update
						else{

							$translate3->Update(array('value' => stripslashes($_POST[$v])), array('id' => $row2['id']));
						}
					}
				}
			}
		}
	}
}
?>