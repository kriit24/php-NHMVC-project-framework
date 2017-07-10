<?
$getName = \Library\DB\Paginator::PAGINATOR_GET;

$exp = explode('?', $_SERVER['REQUEST_URI']);
$GET = $_GET;
if( $GET['route'] == \Conf\Conf::_DEFAULT_ROUTE )
	unset($GET['route']);

echo '<div class="paginator_helper">';
	
	if( $this->showCount )
		echo '<div class="paginator_counter">'.$this->Language( 'Objekte kokku:' ) .' <span>'. $this->q->rowsCount.'</span></div>';

	if( !empty($this->pages) ){

		echo '<a href="' . $this->url( array_merge($GET, array($getName => 1)) ) . ( !empty($exp[1]) ? '?' . $exp[1] : '') . '">' . htmlspecialchars('<<') . '</a>';
		foreach($this->pages as $page => $maxPage){

			echo '<a href="' . $this->url( array_merge($GET, array($getName => $page)) ) . ( !empty($exp[1]) ? '?' . $exp[1] : '') . '" '.($_GET[ $getName ] == $page ? 'class="paginator-active"' : '').'>' . $page . '</a>';
		}
		echo '<a href="' . $this->url( array_merge($GET, array($getName => $maxPage)) ) . ( !empty($exp[1]) ? '?' . $exp[1] : '') . '">' . htmlspecialchars('>>') . '</a>';
	}
	else{

		echo '<div style="clear:both;display:inline-block;"></div>';
	}

echo '</div>';

?>