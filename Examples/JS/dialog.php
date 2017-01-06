<?
//for those examples dialog will be created automatically
echo '<a href="'.$this->url(array('model' => 'ModelName', 'method' => 'PageName')).'" class="dialog">'.$this->Language('Edit').'</a>';
echo '<tr rel="'.$this->url(array('model' => 'ModelName', 'method' => 'PageName')).'" class="dialog"></tr>';

//for this example dialog content will be taken from url
$this->script('$(document).ready(function(){$.dialog.clickAction("www.domain.com/location");});');
?>

AJAX VIEW
dialog ajax post
<form method="post" action="<?=$this->url(array('model' => 'model name', 'method' => 'model method'));?>">if action is empty then dialog will not send post over ajax
</form>