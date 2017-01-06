<?
namespace Model\Dashboard;

class Controller extends \Library{

	protected $methods;

	public function getModels( $inArray = array() ){

		$models = $this->scandir( $this->dir(dirname(__DIR__)) );
		foreach($models as $model){

			if( !empty($inArray) ){

				if( !in_array(basename($model), $inArray) )
					continue;
			}

			if( strtolower(basename($model)) == 'dashboard' )
				continue;

			$class = basename($model);
			if( \Library\Permission::get( 'Model', $class, 'Dashboard' ) ){

				$modelClassName = '\\Model\\'.$class.'\\Index';
				if( method_exists($modelClassName, 'Dashboard') )
					$methods['content'][] = $modelClassName;
			}
		}
		$this->methods = $methods;
		return $this;
	}
}

?>