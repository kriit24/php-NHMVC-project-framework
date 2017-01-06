<?
class Library extends Library\Loader\Library{}
class Session extends Library\Session{}
class Register extends Library\Register{}

new Conf\Conf();
\Library\Sql::singleton()->setConnection();
\Library\Redis::singleton()->setConnection();
?>