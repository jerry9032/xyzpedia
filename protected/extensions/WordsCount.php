<?php

class WordsCount {

	static function length($str) {
		if(empty($str)) {
			return 0;
		}
		if(function_exists('mb_strlen')) {
			return mb_strlen($str, 'utf-8');
		} else {
			preg_match_all("/./u", $str, $ar);
			return count($ar[0]);
		}
	}

	static function substr($str, $start=0) {
		if(empty($str)){
			return false;
		}
		if (function_exists('mb_substr')){
			if(func_num_args() >= 3) {
				$end = func_get_arg(2);
				return mb_substr($str, $start, $end, 'utf-8');
			} else {
				mb_internal_encoding("UTF-8");
				return mb_substr($str, $start);
			}
		} else {
			$null = "";
			preg_match_all("/./u", $str, $ar);
			if(func_num_args() >= 3) {
				$end = func_get_arg(2);
				return join($null, array_slice($ar[0], $start, $end));
			} else {
				return join($null, array_slice($ar[0], $start));
			}
		}
	}

	static function parts($len = 0) {

		if ($len <= 0) return 0;
		
		if ($len > 0 && $len <= 337) return 1;
		if ($len > 337 && $len <= 685) return 2;
		if ($len > 685 && $len <= 1033) return 3;
		if ($len > 1033 && $len <= 1370) return 4;
		if ($len > 1370 && $len <= 1718) return 5;
		if ($len > 1718 && $len <= 2066) return 6;
		if ($len > 2066 && $len <= 2403) return 7;
		if ($len > 2403 && $len <= 2751) return 8;
		if ($len > 2751 && $len <= 3099) return 9;

		if ($len > 3099) return 10;
	}

	static function days($parts = 0)
	{
		if ($parts == 0) return 0;

		if ($parts > 0 && $parts <= 3) return 1;
		if ($parts > 3 && $parts <= 6) return 2;
		if ($parts > 6 && $parts <= 9) return 3;
		
		if ($parts > 9) return 4;
	}

	static function split($title, $str)
	{

		$serial = "小宇宙百科XXXXX\n";

		$len = self::length($str);
		$parts = self::parts($len);

		if ($parts == 1) return array(
			array( $title,
				$serial.$str
			));
		if ($parts == 2) return array(
			array( $title,
				$serial."1)".self::substr($str, 0, 337),
						"2)".self::substr($str, 337))
		);
		if ($parts == 3) return array(
			array( $title,
				$serial."1)".self::substr($str, 0, 337),
						"2)".self::substr($str, 337, 348),
						"3)".self::substr($str, 685))
		);
		if ($parts == 4) return array(
			array( $title."(1)",
				$serial."1)".self::substr($str, 0, 337),
						"2)".self::substr($str, 337, 348)),
			array( $title."(2)",
				$serial."3)".self::substr($str, 685, 337),
						"4)".self::substr($str, 1022))
		);
		if ($parts == 5) return array(
			array( $title."(1)",
				$serial."1)".self::substr($str, 0, 337),
						"2)".self::substr($str, 337, 348),
						"3)".self::substr($str, 685, 348)),
			array( $title."(2)",
				$serial."4)".self::substr($str, 1032, 337),
						"5)".self::substr($str, 1369))
		);
		if ($parts == 6) return array(
			array( $title."(1)",
				$serial."1)".self::substr($str, 0, 337),
						"2)".self::substr($str, 337, 348),
						"3)".self::substr($str, 685, 348)),
			array( $title."(2)",
				$serial."4)".self::substr($str, 1032, 337),
						"5)".self::substr($str, 1369, 348),
						"6)".self::substr($str, 1717))
		);
		if ($parts == 7) return array(
			array( $title."(1)",
				$serial."1)".self::substr($str, 0, 337),
						"2)".self::substr($str, 337, 348),
						"3)".self::substr($str, 685, 348)),
			array( $title."(2)",
				$serial."4)".self::substr($str, 1032, 337),
						"5)".self::substr($str, 1369, 348)),
			array( $title."(3)",
				$serial."6)".self::substr($str, 1717, 337),
						"7)".self::substr($str, 2054))
		);
		if ($parts == 8) return array(
			array( $title."(1)",
				$serial."1)".self::substr($str, 0, 337),
						"2)".self::substr($str, 337, 348),
						"3)".self::substr($str, 685, 348)),
			array( $title."(2)",
				$serial."4)".self::substr($str, 1032, 337),
						"5)".self::substr($str, 1369, 348),
						"6)".self::substr($str, 1717, 348)),
			array( $title."(3)",
				$serial."7)".self::substr($str, 1717, 337),
						"8)".self::substr($str, 2054, 348))
		);
		if ($parts == 9) return array(
			array( $title."(1)",
				$serial."1)".self::substr($str, 0, 337),
						"2)".self::substr($str, 337, 348),
						"3)".self::substr($str, 685, 348)),
			array( $title."(2)",
				$serial."4)".self::substr($str, 1032, 337),
						"5)".self::substr($str, 1369, 348),
						"6)".self::substr($str, 1717, 348)),
			array( $title."(3)",
				$serial."7)".self::substr($str, 1717, 337),
						"8)".self::substr($str, 2054, 348),
						"9)".self::substr($str, 2402, 348))
		);
		return null;
	}
}
?>
