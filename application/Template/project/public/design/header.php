<!DOCTYPE HTML>
<html lang="<?=array_flip(\Conf\Conf::LANGUAGE)[ _LANG ];?>">
<head>
<title><? $this->content('title'); ?></title>
<meta http-equiv="cache-control" content="no-cache"/>
<meta http-equiv="pragma" content="no-cache"/>
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="<? $this->content('description'); ?>"/>
<meta name="keywords" content="<? $this->content('keyword'); ?>"/>
<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE"/>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css" type="text/css"/>
<link rel="stylesheet" href="/Template/css/bootstrap/css/bootstrap.min.css" type="text/css"/>
<link rel="stylesheet" href="/Template/css/font-awesome/css/font-awesome.min.css" type="text/css"/>
<link rel="stylesheet" href="/Template/css/flag-icon-css/css/flag-icon.required.css" type="text/css"/>
<link rel="stylesheet" href="/Template/css/style.css" type="text/css"/>
<link rel="stylesheet" href="/Template/css/fixes.css" type="text/css"/>
<link rel="stylesheet" href="/Template/public/css/style.css" type="text/css"/>
<link rel="stylesheet" href="/Template/public/css/media.css" type="text/css"/>

<?=implode("", $this->header['CSS']);?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js" type="text/javascript"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js" type="text/javascript"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js" type="text/javascript"></script>
<script src="/Template/js/project.js" type="text/javascript"></script>
<script src="/Template/js/project.dialog.js" type="text/javascript"></script>
<script src="/Template/js/project.storage.js" type="text/javascript"></script>

<script type="text/javascript">
var $_POST = $.canJSON('<?=addslashes(json_encode($_POST));?>');
var $_GET = $.canJSON('<?=json_encode($_GET);?>');
var language = '<?=_LANG;?>';
</script>

<?=implode("", $this->header['JS']);?>
</head>

<body>