<?
namespace Command\Git;

class Controller extends \Library{

	public function push(){

		$description = '';
		if( !is_Array($this->command) )
			$description = $this->command;

		system('git add -A');
		system('git commit -a -m \''.$description.'\'');
		system('git push --set-upstream origin master');

		return $this;
	}
}

?>