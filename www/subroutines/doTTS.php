<?php
/*****************************************************************
 * RSS feed thru google
 *****************************************************************/
function doTTS($t,$lang,$sn) 
{
	$hutch = "./vl/hutch/$sn";
	
	if(! is_dir($hutch))
		mkdir($hutch);
	
	//send to google TTS
	$request = "http://translate.google.com/translate_tts?tl=$lang&q=" . urlencode($t);
	
	//curl and save to mp3
	$response = goCurl($request);
	if(strlen($response) < 1) return;  //no response from service
	
	$file="$hutch/rss.mp3";
	writeToFile($file,$response);
}

/*****************************************************************
 * TTS thru Acapella
 *****************************************************************/
function doTTS2($t,$lang,$sn) 
{
	$cwd = getcwd();
	
	if(stripos($cwd,'/vl') > 0)
		$hutch = "./hutch/$sn";
	else
		$hutch = "./vl/hutch/$sn";

	if(! is_dir($hutch)) mkdir($hutch);
	
	//send to Acapella
	$request = "http://www.acapela-group.com/demo-tts/DemoHTML5Form_V2.php";
	$header = 'application/x-www-form-urlencoded';
	$body = 'MyLanguages=sonid10&0=Leila&1=Laia&2=Eliska&3=Mette&4=Zoe&5=Jasmijn&6=Tyler&7=Deepa&8=Rhona&9=Rachel&MySelectedVoice=Sharon&11=Hanna&12=Sanna&13=Justine&14=Louise&15=Manon&16=Claudia&17=Dimitris&18=Fabiana&19=Sakura&20=Minji&21=Lulu&22=Bente&23=Monika&24=Marcia&25=Celia&26=Alyona&27=Biera&28=Ines&29=Rodrigo&30=Elin&31=Samuel&32=Kal&33=Mia&34=Ipek&MyTextForTTS=' . urlencode($t) . '&t=1&SendToVaaS=';
	//curl and save to mp3
	$response = goCurl2($request, $header, $body);
	if(strlen($response) < 1) return;  //no response from service
	
	//pick off the mp3 url from the acapella response
	$pos = stripos($response, '.mp3');

	if($pos) {
		$top = substr($response, 0, $pos);
		$beg = strrpos($top, "'");

		if($beg){
			$len = $pos + 3 - $beg;
			$url = substr($response, $beg + 1, $len);
			//echo 'acapella url is ' . $url;
	
			$response = goCurl($url);
			if(strlen($response) < 1) return;

			$file="$hutch/rss.mp3";
			writeToFile($file,$response);
			writeToFile("$hutch/rss2.txt", $response);
			return $response;
		}
	}
}	

?>