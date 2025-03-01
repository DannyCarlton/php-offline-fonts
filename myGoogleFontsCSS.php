<?php
error_reporting(E_ALL);

$cache_folder = $_SERVER["DOCUMENT_ROOT"].'/data/GoogleFonts-cache/';
$fonts_cache_folder = $_SERVER["DOCUMENT_ROOT"].'/fonts/';
$fonts_client_cache_folder = '/fonts/';
$file_extention = ".css";
$google_url = "https://fonts.gstatic.com/s/";


// DO NOT TOUCH PAST THIS POINT
header('Content-type:text/css');
$font_error = null;
if (!(isset($_SERVER['HTTP_USER_AGENT'])) || $_SERVER['HTTP_USER_AGENT'] == "") 
	{
	$msg = "/* Could not load fonts! Please use the user agent HTTP header. */";
	if ($font_error != null) 
		{
		echo "\n".$msg;
		}
	else 
		{
		echo $msg;
		}
	$font_error = "user_agent";
	}

if (!(isset($_SERVER['QUERY_STRING'])) || $_SERVER['QUERY_STRING'] == "") 
	{
	$msg = "/* Could not load fonts! Please specify fonts with the query string. */";
	if ($font_error != null) 
		{
		echo "\n".$msg;
		}
	else 
		{
		echo $msg;
		}
	$font_error = "query_string";
	}

if ($font_error != null) 
	{
    die();
	}

$query_stringMD5 = md5($_SERVER['QUERY_STRING']);
#$user_agentMD5 = md5($_SERVER['HTTP_USER_AGENT']);
$user_agentMD5 = '';
$file_name = $query_stringMD5 . " " . $user_agentMD5;
$file_path = $cache_folder.$file_name.$file_extention;

if (file_exists($file_path)) 
	{
    $font_css = file_get_contents($file_path);
    $font_css = substr( $font_css, strpos($font_css, "\n")+1 ); // Remove first line
    $font_css = substr( $font_css, strpos($font_css, "\n")+1 ); // Remove first two lines
    $font_css = substr( $font_css, strpos($font_css, "\n")+1 ); // Remove first three lines
    $font_css = substr( $font_css, strpos($font_css, "\n")+1 ); // Remove first four lines
    $font_css = substr( $font_css, strpos($font_css, "\n")+1 ); // Remove first five lines
    echo $font_css;
	}
else 
	{
    $font_css = fetchFontsCss();
    
    $font_css_urls = extract_css_urls($font_css);
    
    foreach ($font_css_urls as &$css_url) 
		{
        $parsed_url = parse_url($css_url);
        $path = $parsed_url['path'];
        $path_array = explode("/", $path); // Pull it apart
        array_shift($path_array); // Pop the first index off array
        array_shift($path_array); // Pop the second index off array
#        array_shift($path_array); // Pop the second index off array
        $path = implode("/", $path_array); // Put it together again
        $path = implode("/", $path_array); // Put it together again
        $path = $fonts_cache_folder . $path; // Put it together again
        if (!file_exists($path)) 
			{
            $path_dir = dirname($path);
            if (!file_exists($path_dir)) 
				{
                mkdir($path_dir, 0700, true);
          	  }
            downloadFile($css_url, $path);
        	}
    	}
    
    $font_css_save = str_replace($google_url, $fonts_client_cache_folder, $font_css);
    echo $font_css_save;
    $font_css_save =
        "/*\n     Fetched: " . date("Y-m-d H:i:s")
        . "\n     Query String: " . $_SERVER['QUERY_STRING']
        . "\n     User Agent: " . $_SERVER['HTTP_USER_AGENT']
        . "\n*/\n" . $font_css_save;
    file_put_contents($file_path, $font_css_save, LOCK_EX);
	}


function fetchFontsCss() 
	{
    $opts = array
		('http' =>
        array
			(
            'method'     => 'GET',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            //'header'   => 'Content-type: application/x-www-form-urlencoded',
            //'content'  => $postdata            
			)
		);

    $context = stream_context_create($opts);

    $result = file_get_contents('https://fonts.googleapis.com/css?' . $_SERVER['QUERY_STRING'], false, $context);
    return $result;
	}


function downloadFile($url, $path) 
	{ // From: http://stackoverflow.com/a/3938844/2852590
	$newfname = $path;
	$file = fopen ($url, "rb");
	if ($file) 
		{
		$newf = fopen ($newfname, "wb");
		if ($newf)
		while(!feof($file)) 
			{
			fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 );
			}
		}

	if ($file) 
		{
		fclose($file);
		}

	if ($newf) 	
		{
		fclose($newf);
		}
 	}

function extract_css_urls($text) // From: http://nadeausoftware.com/articles/2008/01/php_tip_how_extract_urls_css_file
	{
    $urls = array( );
 
    $url_pattern     = '(([^\\\\\'", \(\)]*(\\\\.)?)+)';
    $urlfunc_pattern = 'url\(\s*[\'"]?' . $url_pattern . '[\'"]?\s*\)';
    $pattern         = '/(' .
         '(@import\s*[\'"]' . $url_pattern     . '[\'"])' .
        '|(@import\s*'      . $urlfunc_pattern . ')'      .
        '|('                . $urlfunc_pattern . ')'      .  ')/iu';
    if ( !preg_match_all( $pattern, $text, $matches ) )
        return $urls;
 
    // @import '...'
    // @import "..."
    foreach ( $matches[3] as $match )
        if ( !empty($match) )
            //$urls['import'][] = 
            $urls[] = 
                preg_replace( '/\\\\(.)/u', '\\1', $match );
 
    // @import url(...)
    // @import url('...')
    // @import url("...")
    foreach ( $matches[7] as $match )
        if ( !empty($match) )
            //$urls['import'][] = 
            $urls[] = 
                preg_replace( '/\\\\(.)/u', '\\1', $match );
 
    // url(...)
    // url('...')
    // url("...")
    foreach ( $matches[11] as $match )
        if ( !empty($match) )
            //$urls['property'][] = 
            $urls[] = 
                preg_replace( '/\\\\(.)/u', '\\1', $match );
 
    return $urls;
	}
?>
