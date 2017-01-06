<?PHP

//if($_SESSION['userData']['type'] != 'SUPERADMIN')
//die('<center>Uuendamisel</center>');

set_include_path(dirname(__DIR__));
define('_APPLICATION_ENV', 'admin');
define('_APPLICATION_PATH', 'application');

require dirname(__DIR__).'/'._APPLICATION_PATH.'/Conf/Conf.php';
require 'Library/Loader/Abstract.php';

new class extends Application{};

?>