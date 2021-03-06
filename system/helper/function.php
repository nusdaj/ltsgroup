<?php

	function text($x){
		if(is_array($x)) return $x;
		
		return trim(strip_tags(html($x)));
	}
	
	function dynamic($url = '', $json = true){
			$response = false;

		if ( !$response && ini_get('allow_url_fopen') ){
			$response = file_get_contents($url);
		} 

		if ( !$response && function_exists('curl_version') ){
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			$response = curl_exec($ch);
			curl_close($ch);
		}

		if($json && $response){
			$response = json_decode($response, true);
		}

		return $response;
	}
	
	function endlist(){
		return array("\n", "\r", PHP_EOL, "\n\n", "\r\n");
	}

	function str_lreplace($search, $replace, $subject){
		$pos = strrpos($subject, $search);

		if($pos !== false){
			$subject = substr_replace($subject, $replace, $pos, strlen($search));
		}

		return $subject;
	}
	
	function strposa($haystack, $needles=array(), $offset=0) {
			$chr = array();
			foreach($needles as $needle) {
					$res = strpos($haystack, $needle, $offset);
					if ($res !== false) $chr[$needle] = $res;
			}
			if(empty($chr)) return false;
			return min($chr);
	}

	function str_replace_first($from, $to, $subject){
		$from = '/'.preg_quote($from, '/').'/';

		return preg_replace($from, $to, $subject, 1);
	}
	
	function safe_encode($x){return base64_encode(serialize($x));}
	function safe_decode($x){return unserialize(base64_decode($x));}
	
	function html($x){
		return html_entity_decode($x, ENT_QUOTES, 'UTF-8');
	}
	
	function undescore($x){
		if($x){
			$x = "_".str_replace("-", "_", generateSlug($x));
		}
		return $x;
	}
	
	function create( &$data , $dynamic = array() , $var = '', $default = ''){
		if($data && $dynamic && $var){ //debug($var);
			if(($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST[$var])){
				$data[$var] = $_post[$var];
			}
			elseif(($_SERVER['REQUEST_METHOD'] == 'GET') && isset($_POST[$var])){
				$data[$var] = $_POST[$var];
			}
			elseif(isset($dynamic[$var])){
				$data[$var] = $dynamic[$var];
			}
			else{
				$data[$var] = $default;
			}
			}elseif($var){
			$data[$var] = $default;
			}
		}
		
		function ISO8601ToSeconds($ISO8601){
			$interval = new \DateInterval($ISO8601);
			
			return ($interval->d * 24 * 60 * 60) +
			($interval->h * 60 * 60) +
			($interval->i * 60) +
			$interval->s;
		}
		
		
		function choice($name = null, $id = null, $value = null, $arry){
	
			echo "<select name = '$name' id = '$id' class='form-control' >";
			if(is_array($arry)){
				foreach($arry as $v => $text){
					echo "<option value = '$v' ".(($v == $value)?'selected':'')." >$text</option>";
				}
			}
			echo "</select>";
		}
		
		function select($name = null, $id = null, $value = null, $true = "Yes", $false = "No"){
			echo "<select name = '$name' id = '$id' class='form-control' >";
			echo "<option value = '1' ".($value?'selected':'')." >$true</option>";
			echo "<option value = '0' ".(!$value?'selected':'')." >$false</option>";
			echo "</select>";
		}
		
		function cl($x){
			if(is_array($x)){
				foreach($x as $in => $c){
					echo "<script type = 'text/javascript'>console.log('Index: ".$in."');</script>";
					cl($c);
				}
				}else{
				echo "<script type = 'text/javascript'>console.log('".$x."');</script>";
			}
		}
		
		function ucase($x){return strtoupper($x);}
		function lcase($x){return strtolower($x);}
		
		function debug($array) {
			echo '<pre>';
			print_r($array);
			echo '</pre>';
		}
		
		function debugInfo($array) {
			echo '<pre>';
			var_dump($array);
			echo '</pre>';
		}
		
		function requestServer() {
			echo '<br />PHP_SELF: ' . $_SERVER['PHP_SELF'];
			echo '<br />GATEWAY_INTERFACE: ' . $_SERVER['GATEWAY_INTERFACE'];
			echo '<br />SERVER_ADDR: ' . $_SERVER['SERVER_ADDR'];
			echo '<br />SERVER_NAME: ' . $_SERVER['SERVER_NAME'];
			echo '<br />SERVER_SOFTWARE: ' . $_SERVER['SERVER_SOFTWARE'];
			echo '<br />SERVER_PROTOCOL: ' . $_SERVER['SERVER_PROTOCOL'];
			echo '<br />REQUEST_METHOD: ' . $_SERVER['REQUEST_METHOD'];
			echo '<br />REQUEST_TIME: ' . $_SERVER['REQUEST_TIME'];
			echo '<br />REQUEST_TIME_FLOAT: ' . $_SERVER['REQUEST_TIME_FLOAT'];
			echo '<br />QUERY_STRING: ' . $_SERVER['QUERY_STRING'];
			echo '<br />DOCUMENT_ROOT: ' . $_SERVER['DOCUMENT_ROOT'];
			echo '<br />HTTP_ACCEPT: ' . $_SERVER['HTTP_ACCEPT'];
			echo '<br />HTTP_ACCEPT_CHARSET: ' . $_SERVER['HTTP_ACCEPT_CHARSET'];
			echo '<br />HTTP_ACCEPT_ENCODING: ' . $_SERVER['HTTP_ACCEPT_ENCODING'];
			echo '<br />HTTP_ACCEPT_LANGUAGE: ' . $_SERVER['HTTP_ACCEPT_LANGUAGE'];
			echo '<br />HTTP_CONNECTION: ' . $_SERVER['HTTP_CONNECTION'];
			echo '<br />HTTP_HOST: ' . $_SERVER['HTTP_HOST'];
			echo '<br />HTTP_REFERER: ' . $_SERVER['HTTP_REFERER'];
			echo '<br />HTTP_USER_AGENT: ' . $_SERVER['HTTP_USER_AGENT'];
			echo '<br />HTTPS: ' . $_SERVER['HTTPS'];
			echo '<br />REMOTE_ADDR: ' . $_SERVER['REMOTE_ADDR'];
			echo '<br />REMOTE_HOST: ' . $_SERVER['REMOTE_HOST'];
			echo '<br />REMOTE_PORT: ' . $_SERVER['REMOTE_PORT'];
			echo '<br />REMOTE_USER: ' . $_SERVER['REMOTE_USER'];
			echo '<br />REDIRECT_REMOTE_USER: ' . $_SERVER['REDIRECT_REMOTE_USER'];
			echo '<br />SCRIPT_FILENAME: ' . $_SERVER['SCRIPT_FILENAME'];
			echo '<br />SERVER_ADMIN: ' . $_SERVER['SERVER_ADMIN'];
			echo '<br />SERVER_PORT: ' . $_SERVER['SERVER_PORT'];
			echo '<br />SERVER_SIGNATURE: ' . $_SERVER['SERVER_SIGNATURE'];
			echo '<br />PATH_TRANSLATED: ' . $_SERVER['PATH_TRANSLATED'];
			echo '<br />SCRIPT_NAME: ' . $_SERVER['SCRIPT_NAME'];
			echo '<br />REQUEST_URI: ' . $_SERVER['REQUEST_URI'];
			echo '<br />PHP_AUTH_DIGEST: ' . $_SERVER['PHP_AUTH_DIGEST'];
			echo '<br />PHP_AUTH_USER: ' . $_SERVER['PHP_AUTH_USER'];
			echo '<br />PHP_AUTH_PW: ' . $_SERVER['PHP_AUTH_PW'];
			echo '<br />AUTH_TYPE: ' . $_SERVER['AUTH_TYPE'];
			echo '<br />PATH_INFO: ' . $_SERVER['PATH_INFO'];
			echo '<br />ORIG_PATH_INFO: ' . $_SERVER['ORIG_PATH_INFO'];
		}
		
		function setConfigSetting() {
			$config = new Config();
			
			$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
			
			$query = $db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE store_id = '0' OR store_id = '" . (int)$config->get('config_store_id') . "' ORDER BY store_id ASC");
			
			foreach ($query->rows as $result) {
				if (!$result['serialized']) {
					$config->set($result['key'], $result['value']);
					} else {
					$config->set($result['key'], unserialize($result['value']));
				}
			}
			
			return $config;
		}
		
		function sendMail($to, $from, $sender, $subject, $html = null, $text = null, $admin = false) {
			$config = setConfigSetting();
			
			$mail = new Mail();
			$mail->protocol = $config->get('config_mail_protocol');
			$mail->parameter = $config->get('config_mail_parameter');
			$mail->smtp_hostname = $config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $config->get('config_mail_smtp_timeout');
			
			$mail->setTo($to);
			$mail->setFrom($from);
			$mail->setSender(html_entity_decode($sender, ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			
			if ($html) {
				$mail->setHtml($html);
			}
			
			if ($text) {
				$mail->setText($text);
			}
			
			$mail->send();
			
			// Send to additional alert emails
			if ($admin) {
				$emails = explode(',', $config->get('config_mail_alert'));
				
				foreach ($emails as $email) {
					if ($email && preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $email)) {
						$mail->setTo($email);
						$mail->send();
					}
				}
			}
		}
		
		function _transliteration_process($string, $unknown = '?', $source_langcode = NULL) {
			if (!preg_match('/[\x80-\xff]/', $string)) {
				return $string;
			}
			
			static $tailBytes;
			
			if (!isset($tailBytes)) {
				
				$tailBytes = array();
				for ($n = 0; $n < 256; $n++) {
					if ($n < 0xc0) {
						$remaining = 0;
					}
					elseif ($n < 0xe0) {
						$remaining = 1;
					}
					elseif ($n < 0xf0) {
						$remaining = 2;
					}
					elseif ($n < 0xf8) {
						$remaining = 3;
					}
					elseif ($n < 0xfc) {
						$remaining = 4;
					}
					elseif ($n < 0xfe) {
						$remaining = 5;
					}
					else {
						$remaining = 0;
					}
					$tailBytes[chr($n)] = $remaining;
				}
			}
			
			preg_match_all('/[\x00-\x7f]+|[\x80-\xff][\x00-\x40\x5b-\x5f\x7b-\xff]*/', $string, $matches);
			
			$result = '';
			foreach ($matches[0] as $str) {
				if ($str[0] < "\x80") {
					
					$result .= $str;
					continue;
				}
				
				$head = '';
				$chunk = strlen($str);
				
				$len = $chunk + 1;
				
				for ($i = -1; --$len;) {
					$c = $str[++$i];
					if ($remaining = $tailBytes[$c]) {
						
						$sequence = $head = $c;
						do {
							
							if (--$len && ($c = $str[++$i]) >= "\x80" && $c < "\xc0") {
								
								$sequence .= $c;
							}
							else {
								if ($len == 0) {
									
									$result .= $unknown;
									break 2;
								}
								else {
									
									$result .= $unknown;
									
									--$i;
									++$len;
									continue 2;
								}
							}
						} while (--$remaining);
						
						$n = ord($head);
						if ($n <= 0xdf) {
							$ord = ($n - 192) * 64 + (ord($sequence[1]) - 128);
						}
						elseif ($n <= 0xef) {
							$ord = ($n - 224) * 4096 + (ord($sequence[1]) - 128) * 64 + (ord($sequence[2]) - 128);
						}
						elseif ($n <= 0xf7) {
							$ord = ($n - 240) * 262144 + (ord($sequence[1]) - 128) * 4096 + (ord($sequence[2]) - 128) * 64 + (ord($sequence[3]) - 128);
						}
						elseif ($n <= 0xfb) {
							$ord = ($n - 248) * 16777216 + (ord($sequence[1]) - 128) * 262144 + (ord($sequence[2]) - 128) * 4096 + (ord($sequence[3]) - 128) * 64 + (ord($sequence[4]) - 128);
						}
						elseif ($n <= 0xfd) {
							$ord = ($n - 252) * 1073741824 + (ord($sequence[1]) - 128) * 16777216 + (ord($sequence[2]) - 128) * 262144 + (ord($sequence[3]) - 128) * 4096 + (ord($sequence[4]) - 128) * 64 + (ord($sequence[5]) - 128);
						}
						$result .= _transliteration_replace($ord, $unknown, $source_langcode);
						$head = '';
					}
					elseif ($c < "\x80") {
						
						$result .= $c;
						$head = '';
					}
					elseif ($c < "\xc0") {
						
						if ($head == '') {
							$result .= $unknown;
						}
					}
					else {
						
						$result .= $unknown;
						$head = '';
					}
				}
			}
			return $result;
		}
		
		function _transliteration_replace($ord, $unknown = '?', $langcode = NULL) {
			static $map = array();
			
			$bank = $ord >> 8;
			
			if (!isset($map[$bank][$langcode])) {
				$file = dirname(__FILE__) . '/trans_db/' . sprintf('x%02x', $bank) . '.php';
				if (file_exists($file)) {
					include $file;
					if ($langcode != 'en' && isset($variant[$langcode])) {
						
						$map[$bank][$langcode] = $variant[$langcode] + $base;
					}
					else {
						$map[$bank][$langcode] = $base;
					}
				}
				else {
					$map[$bank][$langcode] = array();
				}
			}
			
			$ord = $ord & 255;
			
			return isset($map[$bank][$langcode][$ord]) ? $map[$bank][$langcode][$ord] : $unknown;
		}
		
		function generateSlug($phrase) {
			$cyr = array(
			"??"=>"i","??"=>"c","??"=>"u","??"=>"k","??"=>"e","??"=>"n",
			"??"=>"g","??"=>"sh","??"=>"sh","??"=>"z","??"=>"x","??"=>"\'",
			"??"=>"f","??"=>"i","??"=>"v","??"=>"a","??"=>"p","??"=>"r",
			"??"=>"o","??"=>"l","??"=>"d","??"=>"zh","??"=>"ie","??"=>"e",
			"??"=>"ya","??"=>"ch","??"=>"c","??"=>"m","??"=>"i","??"=>"t",
			"??"=>"\'","??"=>"b","??"=>"yu",
			"??"=>"I","??"=>"C","??"=>"U","??"=>"K","??"=>"E","??"=>"N",
			"??"=>"G","??"=>"SH","??"=>"SH","??"=>"Z","??"=>"X","??"=>"\'",
			"??"=>"F","??"=>"I","??"=>"V","??"=>"A","??"=>"P","??"=>"R",
			"??"=>"O","??"=>"L","??"=>"D","??"=>"ZH","??"=>"IE","??"=>"E",
			"??"=>"YA","??"=>"CH","??"=>"C","??"=>"M","??"=>"I","??"=>"T",
			"??"=>"\'","??"=>"B","??"=>"YU"
			); 
			
			$gr = array(
			"??" => "V", "??" => "Y", "??" => "Th", "??" => "E", "??" => "Z", "??" => "E",
			"??" => "Th", "??" => "i", "??" => "K", "??" => "L", "??" => "M", "??" => "N",
			"??" => "X", "??" => "O", "??" => "P", "??" => "R", "??" => "S", "??" => "T",
			"??" => "E", "??" => "F", "??" => "Ch", "??" => "Ps", "??" => "O", "??" => "a",
			"??" => "v", "??" => "y", "??" => "th", "??" => "e", "??" => "z", "??" => "e",
			"??" => "th", "??" => "i", "??" => "k", "??" => "l", "??" => "m", "??" => "n",
			"??" => "x", "??" => "o", "??" => "p", "??" => "r", "??" => "s", "??" => "t",
			"??" => "e", "??" => "f", "??" => "ch", "??" => "ps", "??" => "o", "??" => "s",
			"??" => "s", "??" => "s", "??" => "s", "??" => "e", "??" => "i", "??" => "a",
			"??" => "e", "??" => "o", "??" => "o"
			);
			
			$arabic = array(
			"??"=>"a", "??"=>"a", "??"=>"a", "??"=>"e", "??"=>"b", "??"=>"t", "??"=>"th", "??"=>"j",
			"??"=>"h", "??"=>"kh", "??"=>"d", "??"=>"d", "??"=>"r", "??"=>"z", "??"=>"s", "??"=>"sh",
			"??"=>"s", "??"=>"d", "??"=>"t", "??"=>"z", "??"=>"'e", "??"=>"gh", "??"=>"f", "??"=>"q",
			"??"=>"k", "??"=>"l", "??"=>"m", "??"=>"n", "??"=>"h", "??"=>"w", "??"=>"y", "??"=>"a",
			"??"=>"'e", "??"=>"'",   
			"??"=>"'e", "????"=>"la", "??"=>"h", "??"=>"?", "!"=>"!", 
			"??"=>"", 
			"??"=>",", 
			"?????"=>"a", "??"=>"u", "?????"=>"e", "??"=>"un", "??"=>"an", "??"=>"en", "??"=>""
			);
			
			$persian = array(
			"??"=>"a", "??"=>"a", "??"=>"a", "??"=>"e", "??"=>"b", "??"=>"t", "??"=>"th",
			"??"=>"j", "??"=>"h", "??"=>"kh", "??"=>"d", "??"=>"d", "??"=>"r", "??"=>"z",
			"??"=>"s", "??"=>"sh", "??"=>"s", "??"=>"d", "??"=>"t", "??"=>"z", "??"=>"'e",
			"??"=>"gh", "??"=>"f", "??"=>"q", "??"=>"k", "??"=>"l", "??"=>"m", "??"=>"n",
			"??"=>"h", "??"=>"w", "??"=>"y", "??"=>"a", "??"=>"'e", "??"=>"'", 
			"??"=>"'e", "????"=>"la", "??"=>"ke", "??"=>"pe", "??"=>"che", "??"=>"je", "??"=>"gu",
			"??"=>"a", "??"=>"", "??"=>"h", "??"=>"?", "!"=>"!", 
			"??"=>"", 
			"??"=>",", 
			"?????"=>"a", "??"=>"u", "?????"=>"e", "??"=>"un", "??"=>"an", "??"=>"en", "??"=>""
			);
			
			$normalize = array(
			'??'=>'S', '??'=>'s', '??'=>'Dj','??'=>'Z', '??'=>'z', '??'=>'A', '??'=>'A', '??'=>'A', '??'=>'A', '??'=>'A',
			'??'=>'A', '??'=>'A', '??'=>'C', '??'=>'E', '??'=>'E', '??'=>'E', '??'=>'E', '??'=>'I', '??'=>'I', '??'=>'I',
			'??'=>'I', '??'=>'N', '??'=>'O', '??'=>'O', '??'=>'O', '??'=>'O', '??'=>'O', '??'=>'O', '??'=>'U', '??'=>'U',
			'??'=>'U', '??'=>'U', '??'=>'Y', '??'=>'B', '??'=>'Ss','??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a',
			'??'=>'a', '??'=>'a', '??'=>'c', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'i', '??'=>'i', '??'=>'i',
			'??'=>'i', '??'=>'o', '??'=>'n', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'u',
			'??'=>'u', '??'=>'u', '??'=>'y', '??'=>'y', '??'=>'b', '??'=>'y', '??'=>'f', '??'=>'G', '??'=>'S', '??'=>'U',
			'??'=>'u', '???'=>'Z', '???'=>'z', '??'=>'N', '??'=>'n', '??'=>'O', '??'=>'o', '??'=>'U', '??'=>'u', '???'=>'W',
			'???'=>'w', '???'=>'Y', '???'=>'y', '??'=>'c', '??'=>'C', '??'=>'a', '??'=>'A', '??'=>'c', '??'=>'C', '??'=>'d', 
			'??'=>'D', '??'=>'e', '??'=>'E', '??'=>'e', '??'=>'E', '??'=>'i', '??'=>'I', '??'=>'n', '??'=>'N', '??'=>'o', 
			'??'=>'O', '??'=>'r', '??'=>'R', '??'=>'s', '??'=>'S', '??'=>'t', '??'=>'T', '??'=>'u', '??'=>'U', '??'=>'u', 
			'??'=>'U', '??'=>'y', '??'=>'Y', '??'=>'z', '??'=>'Z', "??"=>'a', '??'=>'A', '??'=>'c', '??'=>'C', '??'=>'e',
			'??'=>'E', '??'=>'l', '??'=>'n', '??'=>'o', '??'=>'s', '??'=>'S', '??'=>'z', '??'=>'Z', '??'=>'z', '??'=>'Z',
			'??'=>'i', '??'=>'s', '??'=>'g', '??'=>'i'  
			);
			
			$result = html_entity_decode($phrase, ENT_COMPAT, "UTF-8"); 
			
			$result = strtr($result, $cyr);
			$result = strtr($result, $gr);
			$result = strtr($result, $arabic);
			$result = strtr($result, $persian);
			$result = strtr($result, $normalize);   
			$result = strtolower(_transliteration_process($result)); 
			
			$result = strtolower($result);
			$result = str_replace('&', '-and-', $result);
			$result = str_replace('^', '', $result);
			$result = preg_replace("/[^a-z0-9-]/", "-", $result);
			$result = preg_replace('{(-)\1+}','$1', $result); 
			$result = trim(substr($result, 0, 800));
			$result = trim($result,'-');
			
			return $result;
		}

		function getDayText($n) {
			$days = array(1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 7 => 'Sunday');
			return $days[$n];
		}	
		