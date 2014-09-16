<?php
// unicode substr workaround from http://en.jinzorahelp.com/forums/viewtopic.php?f=18&t=6231
function substr_utf8($str,$from,$len)
{
	if ( function_exists('mb_substr') ) {
		return mb_substr($str,$from,$len);
	} else {
		# utf8 substr
		# http://www.yeap.lv
		return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.
		'((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s',
		'$1',$str);
	}
}

// dirty hack for missing mb_strlen() found at http://www.php.net/manual/en/function.mb-strlen.php#87114
function mb_strlen_dh($utf8str)
{
	if ( function_exists('mb_strlen') ) {
		return mb_strlen($utf8str);
	} else {
		return preg_match_all('/.{1}/us', $utf8str, $dummy);
	}
}