<?
namespace Helper\Controller;

//\Helper\Controller\Reference::get($someid);

class Reference{

	static function get($prime_number){

		$coefficient = 7;
		$ref = $prime_number;

		//731 meetodi rakendamine k�ikidele arvudele
		do {

				//Korrutised liidetakse
				$check_digits = $check_digits + (($ref % 10) * $coefficient);

				if ($coefficient == 7) $coefficient = 3;
				else if ($coefficient == 3) $coefficient = 1;
				else if ($coefficient == 1) $coefficient = 7;

		} while ((int)$ref /= 10);

		//L�him suurem k�mnekordne arv
		$check_digits = 10 - ($check_digits % 10);

		//Kui arv on 10, siis arv peab olema 0
		if ($check_digits == 10){$check_digits = 0;}

		return $prime_number.$check_digits;
	}
}
?>