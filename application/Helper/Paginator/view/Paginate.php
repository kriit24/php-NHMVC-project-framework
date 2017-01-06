<?
$getName = \Library\DB\Paginator::PAGINATOR_GET;
$GET = $_GET;
unset($GET[$getName]);
$url = '';
foreach( explode('&', substr($_SERVER['QUERY_STRING'], strrpos($_SERVER['QUERY_STRING'], '/'))) AS $v ){

	$v = str_replace('/', '', $v);
	list($key, $value) = explode('=', $v);
	$key = preg_replace('/\[( . *?)\]/s', '', $key);
	if( $key != $getName )
		$url .= $url ? '&' . $v : $v;
	unset($GET[$key]);
}
$href = $this->url($GET);
if( substr($href, -1) != '/' )
	$href .= '/';
if( $url )
	$url = (preg_match('/\?/i', $href) ? '&' : '?') . $url;

echo '<div class="paginator_helper">';

echo '<a href="' . $href . $getName . '/1/' . $url . '">' . htmlspecialchars('<<') . '</a>';
foreach($this->pages as $page => $maxPages)
	echo '<a href="' . $href . $getName . '/' . $page . '/' . $url . '">' . $page . '</a>';
echo '<a href="' . $href . $getName . '/' . $maxPages . '/' . $url . '">' . htmlspecialchars('>>') . '</a>';

echo '</div>';

?>