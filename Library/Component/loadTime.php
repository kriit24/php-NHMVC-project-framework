<?
namespace Library\Component;

$loadTimeStart;

class loadTime{

	static function start( $string = '' ){

		global $loadTimeStart;

		$time = microtime();
		$time = explode(' ', $time);
		$loadTimeStart = $time[1] + $time[0];
		if( $string )
			echo '<br><b style="color:red;">'.$string.'</b><br>';
	}

	static function end( $string = '' ){

		global $loadTimeStart;

		$time = microtime();
		$time = explode(' ', $time);
		$time = $time[1] + $time[0];
		$finish = $time;
		$total_time = $finish - $loadTimeStart;
		if( $total_time >= 1 )
			echo '<br>Page: <b style="color:red;">'.$string.'</b> generated in '.number_format($total_time, 4).' seconds.<br>';
		else
			echo '<br>Page: <b style="color:red;">'.$string.'</b> generated in '.number_format(($total_time*1000), 4).' milliseconds.</br>';
	}
}

?>