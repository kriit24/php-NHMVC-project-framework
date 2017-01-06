take actions on live

<? $this->script('
$("elem").live("click", function(data){
	alert(data);
});
');
?>