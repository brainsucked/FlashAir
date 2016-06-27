<?php

$BASE_URL = 'http://domain.dev/flashair/';
error_reporting(0);
set_time_limit(0);

function remoteFileExists($url) {
		$curl = curl_init($url);
		//don't fetch the actual page, you only want to check the connection is ok
		curl_setopt($curl, CURLOPT_NOBODY, true);
		//do request
		$result = curl_exec($curl);
		$ret = false;
		//if request did not fail
		if ($result !== false) {
			//if request was ok, check response code
			$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);  
			if ($statusCode == 200) {
				$ret = true;   
			}
		}
		curl_close($curl);
		return $ret;
}

if(!empty($_GET['url'])) {

	if(file_get_contents($_GET['url'])) $homepage = file_get_contents($_GET['url']);
	else die('FlashAir is offline!');

	$pattern = '/"fname":"(.*?)",/';
	$java_script = $homepage;

	preg_match_all($pattern, $java_script, $matches);

	foreach($matches[1] as $match) {

		if(!remoteFileExists($BASE_URL.'download/'.$match)) {
			copy($_GET['url'].'/'.$match, __DIR__ . DIRECTORY_SEPARATOR.'download'.DIRECTORY_SEPARATOR.$match);
		}

	}
	echo 'Done!';
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>FlashAir Web Downloader!</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<link href="//code.rifix.net/bootstrap/2.3.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<hr>
<div style="text-align:center;">
<h1>FlashAir Web Downloader</h1>
<p>Example: http://flashair/DCIM/100__TSB</p>
<form>
<div class="input-append"><input type="url" name="url" id="appendedInputButton" value="<?php if(!empty($_GET['url'])) echo $_GET['url']; ?>" /><button type="submit" id="go" class="btn">Go</button></div>
</form>
</div>
<hr>
</body>
</html>