<?
namespace Library\Component;

class Extension extends Router{

	const DEBUG = (_DEBUG == 'view' ? true : false);

	use Extension\View, Extension\Msg, Extension\Request;
}

?>