<?
namespace Model\Privilege;

class Form extends \Library{

	const ROUTE = array('Api', 'Model', 'Helper');
	const SUBMIT = array(
		'add' => 'addPrivilege',
		'clone' => 'clonePrivilege',
		'update' => 'updatePrivilege'
	);
	var $roles = array();

	function classList(){

		$contr = new Controller;

		$classes = array();
		foreach(self::ROUTE as $route){

			$classes = array_merge($classes, $contr->getClassListing($route));
		}
		pre($classes);
	}
}

?>