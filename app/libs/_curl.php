<?php

class _curl
{
	static function _file_($url, $dest)
	{
		$i = curl_init();
		curl_setopt($i, CURLOPT_URL, $url);
		$referer = _str::_cut($url, array('beg' => 'http://', 'end' => '/'), 1);
		curl_setopt($i, CURLOPT_REFERER, $referer);
		curl_setopt($i, CURLOPT_HEADER, 0);
		$browser = 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)';
		curl_setopt($i, CURLOPT_USERAGENT, $browser);
		if ( ! $f = fopen($dest, 'w') ) die( "ERROR: la ruta de destino '$dest' no existe." );
		curl_setopt($i, CURLOPT_FILE, $f);
		curl_exec($i);
		if ( $f ) fclose($f);
		curl_close($i);
	}

	static function _str_($url)
	{
		$i = curl_init(); # initialize curl handle
		curl_setopt($i, CURLOPT_URL, $url); # set url to post to
		curl_setopt($i, CURLOPT_FAILONERROR, 1); # Fail on errors
		#curl_setopt($i, CURLOPT_FOLLOWLOCATION, 1); # allow redirects
		curl_setopt($i, CURLOPT_RETURNTRANSFER, 1); # return into a variable
		#curl_setopt($i, CURLOPT_PORT, 80); # Set the port number
		#curl_setopt($i, CURLOPT_TIMEOUT, 15); # times out after 15s
		$browser = 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)';
		curl_setopt($i, CURLOPT_USERAGENT, $browser);
		$code = curl_exec($i);
		curl_close($i);
		return $code;
	}
	
	static function _get_($url, $pat, $dest)
	{
		$code = file_get_contents($url);
		$part = _str::_cut($code, $pat);
		$a = explode('<img ', $part);
		array_shift($a);
		
		foreach ($a as $v)
		{
			$http = strstr('http://', $v);
			$src = _str::_cut($v, array('beg' => 'src="', 'end' => '"') );			
			$domain = _str::_cut($url, array('beg' => 'http://', 'end' => '/'), 1);
			$name = basename($src);
			if ( ! $http) $src = $domain . $src;
			
			_curl::_file_($src, $dest . $name);			
		}
	}
}

?>