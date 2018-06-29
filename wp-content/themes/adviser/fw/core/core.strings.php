<?php
/**
 * Axiomthemes Framework: strings manipulations
 *
 * @package	themerex
 * @since	themerex 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Check multibyte functions
if ( ! defined( 'AXIOMTHEMES_MULTIBYTE' ) ) define( 'AXIOMTHEMES_MULTIBYTE', function_exists('mb_strlen') ? 'UTF-8' : false );

if (!function_exists('axiomthemes_strlen')) {
	function axiomthemes_strlen($text) {
		return AXIOMTHEMES_MULTIBYTE ? mb_strlen($text) : strlen($text);
	}
}

if (!function_exists('axiomthemes_strpos')) {
	function axiomthemes_strpos($text, $char, $from=0) {
		return AXIOMTHEMES_MULTIBYTE ? mb_strpos($text, $char, $from) : strpos($text, $char, $from);
	}
}

if (!function_exists('axiomthemes_strrpos')) {
	function axiomthemes_strrpos($text, $char, $from=0) {
		return AXIOMTHEMES_MULTIBYTE ? mb_strrpos($text, $char, $from) : strrpos($text, $char, $from);
	}
}

if (!function_exists('axiomthemes_substr')) {
	function axiomthemes_substr($text, $from, $len=-999999) {
		if ($len==-999999) { 
			if ($from < 0)
				$len = -$from; 
			else
				$len = axiomthemes_strlen($text)-$from;
		}
		return AXIOMTHEMES_MULTIBYTE ? mb_substr($text, $from, $len) : substr($text, $from, $len);
	}
}

if (!function_exists('axiomthemes_strtolower')) {
	function axiomthemes_strtolower($text) {
		return AXIOMTHEMES_MULTIBYTE ? mb_strtolower($text) : strtolower($text);
	}
}

if (!function_exists('axiomthemes_strtoupper')) {
	function axiomthemes_strtoupper($text) {
		return AXIOMTHEMES_MULTIBYTE ? mb_strtoupper($text) : strtoupper($text);
	}
}

if (!function_exists('axiomthemes_strtoproper')) {
	function axiomthemes_strtoproper($text) {
		$rez = ''; $last = ' ';
		for ($i=0; $i<axiomthemes_strlen($text); $i++) {
			$ch = axiomthemes_substr($text, $i, 1);
			$rez .= axiomthemes_strpos(' .,:;?!()[]{}+=', $last)!==false ? axiomthemes_strtoupper($ch) : axiomthemes_strtolower($ch);
			$last = $ch;
		}
		return $rez;
	}
}

if (!function_exists('axiomthemes_strrepeat')) {
	function axiomthemes_strrepeat($str, $n) {
		$rez = '';
		for ($i=0; $i<$n; $i++)
			$rez .= $str;
		return $rez;
	}
}

if (!function_exists('axiomthemes_strshort')) {
	function axiomthemes_strshort($str, $maxlength, $add='...') {
	//	if ($add && axiomthemes_substr($add, 0, 1) != ' ')
	//		$add .= ' ';
		if ($maxlength < 0) 
			return '';
		if ($maxlength < 1 || $maxlength >= axiomthemes_strlen($str))
			return strip_tags($str);
		$str = axiomthemes_substr(strip_tags($str), 0, $maxlength - axiomthemes_strlen($add));
		$ch = axiomthemes_substr($str, $maxlength - axiomthemes_strlen($add), 1);
		if ($ch != ' ') {
			for ($i = axiomthemes_strlen($str) - 1; $i > 0; $i--)
				if (axiomthemes_substr($str, $i, 1) == ' ') break;
			$str = trim(axiomthemes_substr($str, 0, $i));
		}
		if (!empty($str) && axiomthemes_strpos(',.:;-', axiomthemes_substr($str, -1))!==false) $str = axiomthemes_substr($str, 0, -1);
		return ($str) . ($add);
	}
}

// Clear string from spaces, line breaks and tags (only around text)
if (!function_exists('axiomthemes_strclear')) {
	function axiomthemes_strclear($text, $tags=array()) {
		if (empty($text)) return $text;
		if (!is_array($tags)) {
			if ($tags != '')
				$tags = explode($tags, ',');
			else
				$tags = array();
		}
		$text = trim(chop($text));
		if (count($tags) > 0) {
			foreach ($tags as $tag) {
				$open  = '<'.esc_attr($tag);
				$close = '</'.esc_attr($tag).'>';
				if (axiomthemes_substr($text, 0, axiomthemes_strlen($open))==$open) {
					$pos = axiomthemes_strpos($text, '>');
					if ($pos!==false) $text = axiomthemes_substr($text, $pos+1);
				}
				if (axiomthemes_substr($text, -axiomthemes_strlen($close))==$close) $text = axiomthemes_substr($text, 0, axiomthemes_strlen($text) - axiomthemes_strlen($close));
				$text = trim(chop($text));
			}
		}
		return $text;
	}
}

// Return slug for the any title string
if (!function_exists('axiomthemes_get_slug')) {
	function axiomthemes_get_slug($title) {
		return axiomthemes_strtolower(str_replace(array('\\','/','-',' ','.'), '_', $title));
	}
}
?>