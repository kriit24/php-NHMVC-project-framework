<?
namespace Model\Dashboard;

class Controller extends \Library{

	protected $methods;

	public function getModels(){

		$models = $this->scandir( $this->dir(dirname(__DIR__)) );
		foreach($models as $model){

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