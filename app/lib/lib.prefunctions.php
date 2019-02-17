<?php
 	if(!defined('APP')) die('...');

	function mds($key,$level = 0)
	{
		/**
		| http://randomkeygen.com sitesi ile random key oluşturuyorum :)
		|
		| * Şifre Hatırlat özelliği bulunan bir sistem için şunu önerebilirim
		|
		| Her yıl değişiminde yeniden şifrelemeye zorlamak için
		| $salt2 	= date("Y"); olarak ayarlanması yeterlidir.
		|
		| Her ay yeniden şifrelemeye zorlamak için
		| $salt2 	= date("Ym"); olarak ayarlanması yeterlidir.
		|
		| $salt0 değeri en basit şifreyi sıfır/Zero bile olsa güçlendirecek
		| sayı + harf + şekil + karakter
		| gibi zorlu bir kombinasyondan oluşmalıdır
		|
		| en son aşamada md5 kullanılmasının amacı çıktıyı normal bir
		| md5 çıktısıymış gibi göstermektir
		|
		| $Level değeri
		| md5 yapılmış şifreleri
		| mds şekline dönüştürmek için kullanılır
		|
		| Tek farkı, ilk aşamadaki key değerini md5 yapmak yerine, yapılmış haliyle kullanmasıdır
		|
		| Tayland, Songkhala'da yazılmıştır.
		*/

		$salt0 	= 'XvX0kGG:4{05g9%ijnX4Z7kT^Ia3Hc';
		$salt1 	= 'jBCFQiEm(PM06(i511uKM99Ooos7J['; //bir hash
		$salt2 	= '7!8FBkEM]bzWYoI?{qK{C/HC)wO70y'; //bir başka hash
		$s0 	= md5($salt0);
		$s1 	= md5($salt1);
		$s2 	= md5($salt2);
		if($level == 1 )
		{
			$f0 	= $key.md5($s0);
		}
		else
		{
			$f0 	= md5($key).md5($s0);
		}
		$f0 	= md5($f0);

		//aşama 1
		$f1 = hash('sha1', 		$s0.$f0);
		//aşama 2
		$f2 = hash('sha256', 	$s1.$f1);
		//aşama 3
		$f3 = hash('sha512', 	$s2.$f2);
		//aşama 2 ters
		$f2 = hash('sha256', 	$s1.$f3);
		//aşama 1 ters
		$f1 = hash('sha1', 		$s0.$f2);
		//sadece maskeleme
		$f0 = hash('md5', 		$s2.$f1);

		return $f0;
	}


	function check_email_address($email)
	{
		if(filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function myReq($key, $level = 1, $slash = 0)
	{
		/**
		| DEĞİŞKEN GETİRME FONKSİYONU
		*/

		/**
		| Keyleri request ile varsayılan olarak aldığımızı unutmamak lazım
		| Zaten fonksiyonun amacı keyleri hızlıca alıp değişkene dönüştürmek
		| istenirse bir seçenek daha eklenip, get post reguest veya metodu devre dışı bırakmak
		| imkanı da eklenebilir.
		|
		| $level, hangi seviyede işlem göreceğini
		| $slash, işlemin sonunda slash eklenip eklenmeyeceğini gösterir
		| Örnek Kullanım, doğru yöntem
		| $key = myReq($key,1,1)
		|
		| kullanımı kolaylaştırmak için şu şekillerde kullanma imkanı da var
		| lakin log dosyalarını şişirme ihtimali olduğunu unutmamak lazım
		|
		| $key = myReq($key,1);
		| $key = myReq($key);
		*/

		$key = $_REQUEST[$key];
		if($level == 0) $key = intval(trim($key));
		if($level == 1) $key = trim($key);
		if($level == 2) $key = trim(strip_tags($key));
		if($level == 3)
		{
			if($key == "on")
			{
				$key = 1;
			}
			else
			{
				$key = 0;
			}
		}
		if($slash == 1) $key = addslashes($key);
		return $key;
	}

	function cleanKey($key,$level = 0)
	{
		/**
		| $level, hangi seviyede işlem göreceğini
		*/
		if($level == 0) $key = trim($key);
		if($level == 1) $key = trim(stripslashes($key));
		return $key;
	}

	function keygen($length)
	{
		$key = '';
		list($usec, $sec) = explode(' ', microtime());
		mt_srand((float) $sec + ((float) $usec * 100000));

		$inputs = array_merge(range('z','a'),range(0,9),range('A','Z'));

		for($i=0; $i<$length; $i++)
		{
			$key .= $inputs{mt_rand(0,61)};
		}
		return $key;
	}

	function gen_key($length)
	{
		$options 	= "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890098765432100258369741";
		$code 		= "";
		for($i = 0; $i < $length; $i++)
		{
			$key = rand(0, strlen($options) - 1);
			$code .= $options[$key];
		}
		return $code;
	}

	function gen_id($length)
	{
		$options 	= "1234567890098765432100258369741";
		$code 		= "";
		for($i = 0; $i < $length; $i++)
		{
			$key = rand(0, strlen($options) - 1);
			$code .= $options[$key];
		}
		return $code;
	}

	function format_url($text)
	{
		#-------------------------------------------------
		# phpBB Turkiye ekibi Alexis tarafından 2007 yılında yazılmıştır
		#-------------------------------------------------

		$text = trim($text);
		$text = str_replace("I","ı",$text);
		$text = mb_strtolower($text, 'UTF-8');

		$find = array(' ', '&quot;', '&amp;', '&', '\r\n', '\n', '/', '\\', '+', '<', '>');
		$text = str_replace ($find, '-', $text);

		$find = array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ë', 'Ê');
		$text = str_replace ($find, 'e', $text);

		$find = array('í', 'ı', 'ì', 'î', 'ï', 'I', 'İ', 'Í', 'Ì', 'Î', 'Ï');
		$text = str_replace ($find, 'i', $text);

		$find = array('ó', 'ö', 'Ö', 'ò', 'ô', 'Ó', 'Ò', 'Ô');
		$text = str_replace ($find, 'o', $text);

		$find = array('á', 'ä', 'â', 'à', 'â', 'Ä', 'Â', 'Á', 'À', 'Â');
		$text = str_replace ($find, 'a', $text);

		$find = array('ú', 'ü', 'Ü', 'ù', 'û', 'Ú', 'Ù', 'Û');
		$text = str_replace ($find, 'u', $text);

		$find = array('ç', 'Ç');
		$text = str_replace ($find, 'c', $text);

		$find = array('ş', 'Ş');
		$text = str_replace ($find, 's', $text);

		$find = array('ğ', 'Ğ');
		$text = str_replace ($find, 'g', $text);

		$find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');

		$repl = array('', '-', '');

		$text = preg_replace ($find, $repl, $text);
		$text = str_replace ('--', '-', $text);

		//$text = $text;

		return $text;
	}

	function format_int($text)
	{
		#-------------------------------------------------
		# phpBB Turkiye ekibi Alexis tarafından 2007 yılında yazılmıştır
		#-------------------------------------------------

		$text = trim($text);
		$text = str_replace("I","ı",$text);
		$text = mb_strtolower($text, 'UTF-8');

		$find = array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ë', 'Ê');
		$text = str_replace ($find, 'e', $text);

		$find = array('í', 'ı', 'ì', 'î', 'ï', 'I', 'İ', 'Í', 'Ì', 'Î', 'Ï');
		$text = str_replace ($find, 'i', $text);

		$find = array('ó', 'ö', 'Ö', 'ò', 'ô', 'Ó', 'Ò', 'Ô');
		$text = str_replace ($find, 'o', $text);

		$find = array('á', 'ä', 'â', 'à', 'â', 'Ä', 'Â', 'Á', 'À', 'Â');
		$text = str_replace ($find, 'a', $text);

		$find = array('ú', 'ü', 'Ü', 'ù', 'û', 'Ú', 'Ù', 'Û');
		$text = str_replace ($find, 'u', $text);

		$find = array('ç', 'Ç');
		$text = str_replace ($find, 'c', $text);

		$find = array('ş', 'Ş');
		$text = str_replace ($find, 's', $text);

		$find = array('ğ', 'Ğ');
		$text = str_replace ($find, 'g', $text);

		return $text;
	}

	function tr_strtolower($text)
	{
		#-------------------------------------------------
		# şu adresten alınmıştır
		# http://www.php.net/manual/en/function.strtoupper.php#97667
		#-------------------------------------------------
		return mb_convert_case(str_replace('I','ı',$text), MB_CASE_LOWER, "UTF-8");
	}

	function tr_strtoupper($text)
	{
		#-------------------------------------------------
		# şu adresten alınmıştır
		# http://www.php.net/manual/en/function.strtoupper.php#97667
		#-------------------------------------------------
		return mb_convert_case(str_replace('i','İ',$text), MB_CASE_UPPER, "UTF-8");
	}

	function tr_ucfirst($text)
	{
		#-------------------------------------------------
		# şu adresten alınmıştır
		# http://www.php.net/manual/en/function.ucfirst.php#105435
		#-------------------------------------------------

		$text = str_replace("I","ı",$text);
		$text = mb_strtolower($text, 'UTF-8');

		if($text[0] == "i")
			$tr_text = "İ".substr($text, 1);
		else
			$tr_text = mb_convert_case($text, MB_CASE_TITLE, "UTF-8");

		return trim($tr_text);
	}

	function tr_ucwords($text)
	{
		#-------------------------------------------------
		# şu adresten alınmıştır
		# http://www.php.net/manual/en/function.ucfirst.php#105435
		#-------------------------------------------------
		$p = explode(" ",$text);
		if(is_array($p))
		{
			$tr_text = "";
			foreach($p AS $item)
				$tr_text .= " ".tr_ucfirst($item);

			return trim($tr_text);
		}
		else
			return tr_ucfirst($text);
	}


	function showMessageBoxS($msgText, $msgType)
	{
		if($msgType == 'error')
		{
			$a = '<div class="box box-solid bg-red"><div class="box-body-wp">'.$msgText.'</div></div>';
		}

		if($msgType == 'info')
		{
			$a = '<div class="box box-solid bg-green"><div class="box-body-wp">'.$msgText.'</div></div>';
		}
		return $a;

	}

	function print_pre($s)
	{
		echo "<pre>";
		print_r($s);
		echo "</pre>";
	}

	function url_content
	(
		$title,
		$id
	)
	{
		$url = SITELINK.format_url($title).S_CONTENT.$id.S_CONTENT_EXT;
		return $url;
	}


	function n2br($metin)
	{
		$metin = str_replace(array("\r\n","\r","\n"), "<br />", $metin); // cross-platform newlines
 		$metin = str_replace(array("<br /><br /><br />","<br /><br /><br /><br />","<br /><br /><br /><br /><br />"), "<br /><br />", $metin); // cross-platform newlines
		$metin = trim($metin);
		return $metin;
	}

	function debug_min()
	{
		global $starttime;

		$endtime = microtime(true);
		$endtime = substr(($endtime - $starttime),0,6);

		$kullanim = memory_get_peak_usage(true);
		$kullanim = number_format($kullanim / 1024);

		$content = 'SÜS : '.$endtime.' | MEM : '.$kullanim.'<br/>';
		return $content;
	}

	function xml_rpc_ping($service_url, $content_url)
	{
		global $L;
		$client = new IXR_Client( $service_url );
		$client->timeout = 3;
		$client->useragent .= ' -- PingTool/1.0.0';
		$client->debug = false;

		if( $client->query('weblogUpdates.extendedPing', $L['pIndex_Company'], SITELINK, $content_url, LINK_FEED))
		{
			return $client->getResponse();
		}

		//echo 'Failed extended XML-RPC ping for "' . $service_url . '": ' . $client->getErrorCode() . '->' . $client->getErrorMessage() . '<br />';

		if( $client->query('weblogUpdates.ping', $myBlogName, SITELINK ))
		{
			return $client->getResponse();
		}

		//echo 'Failed basic XML-RPC ping for "' . $service_url . '": ' . $client->getErrorCode() . '->' . $client->getErrorMessage() . '<br />';

		return false;
	}

	function ping_it($content_url)
	{
		require_once(SITEPATH.'vendors/classes/class.ping.php' );

		$dizi_ping = array(
			'http://blogsearch.google.com.tr/ping/RPC2',
			'http://blogsearch.google.com/ping/RPC2',
			'http://blogsearch.google.us/ping/RPC2',
			'https://ping.blogs.yandex.ru/RPC2',
			'http://rpc.pingomatic.com',
		);

		foreach( $dizi_ping as $v )
		{
			xml_rpc_ping($v, $content_url);
		}
	}

	function sesId($length)
	{
		$key = '';
		list($usec, $sec) = explode(' ', microtime());
		mt_srand((float) $sec + ((float) $usec * 103020));

		$inputs = array_merge(range('z','a'),range(0,9),range('A','Z'));

		for($i=0; $i<$length; $i++)
		{
			$key .= $inputs{mt_rand(0,61)};
		}
		return $key;
	}

	function get_real_ip()
	{
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '')
		{
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
			return $_SERVER['REMOTE_ADDR'];
		}
	}

	function pco_format_date($tarih)
	{
		return date('D, d M Y H:i:s O', $tarih);
	}

	function timeConvert($zaman)
	{
		$zaman 			=  strtotime($zaman);
		$zaman_farki 	= time() - $zaman;
		$saniye 		= $zaman_farki;
		$dakika 		= round($zaman_farki/60);
		$saat 			= round($zaman_farki/3600);
		$gun 			= round($zaman_farki/86400);
		$hafta 			= round($zaman_farki/604800);
		$ay 			= round($zaman_farki/2419200);
		$yil 			= round($zaman_farki/29030400);

		if( $saniye < 60 )
		{
			return "az önce";
		}
		else if ( $dakika < 60 )
		{
			return $dakika .' dakika önce';
		}
		else if ( $saat < 24 )
		{
			return $saat.' saat önce';
		}
		else if ( $gun < 7 )
		{
			return $gun .' gün önce';
		}
		else if ( $hafta < 4 )
		{
			return $hafta.' hafta önce';
		}
		else if ( $ay < 12 )
		{
			return $ay .' ay önce';
		}
		else
		{
			return $yil.' yıl önce';
		}
	}


	function nice_number($n)
	{
		// first strip any formatting;
		$n = (0+str_replace(",", "", $n));

		// is this a number?
		if (!is_numeric($n)) return false;

		// now filter it;
		if ($n > 1000000000000) return round(($n/1000000000000), 2).' TR';
		elseif ($n > 1000000000) return round(($n/1000000000), 2).' MR';
		elseif ($n > 1000000) return round(($n/1000000), 2).' MN';
		elseif ($n > 1000) return round(($n/1000), 0).' Bin';

		return number_format($n);
	}

	function date_tr($f, $zt = 'now')
	{
		/**
		* Hiçbir yerde kullanılmamış fonksiyonlardan nefret ediyorum
		*/

		$z = date("$f", strtotime($zt));
		$donustur = array(
				'Monday'	=> 'Pazartesi',
				'Tuesday'	=> 'Salı',
				'Wednesday'	=> 'Çarşamba',
				'Thursday'	=> 'Perşembe',
				'Friday'	=> 'Cuma',
				'Saturday'	=> 'Cumartesi',
				'Sunday'	=> 'Pazar',
				'January'	=> 'Ocak',
				'February'	=> 'Şubat',
				'March'		=> 'Mart',
				'April'		=> 'Nisan',
				'May'		=> 'Mayıs',
				'June'		=> 'Haziran',
				'July'		=> 'Temmuz',
				'August'	=> 'Ağustos',
				'September'	=> 'Eylül',
				'October'	=> 'Ekim',
				'November'	=> 'Kasım',
				'December'	=> 'Aralık',
				'Mon'		=> 'Pts',
				'Tue'		=> 'Sal',
				'Wed'		=> 'Çar',
				'Thu'		=> 'Per',
				'Fri'		=> 'Cum',
				'Sat'		=> 'Cts',
				'Sun'		=> 'Paz',
				'Jan'		=> 'Oca',
				'Feb'		=> 'Şub',
				'Mar'		=> 'Mar',
				'Apr'		=> 'Nis',
				'Jun'		=> 'Haz',
				'Jul'		=> 'Tem',
				'Aug'		=> 'Ağu',
				'Sep'		=> 'Eyl',
				'Oct'		=> 'Eki',
				'Nov'		=> 'Kas',
				'Dec'		=> 'Ara',
		);
		foreach($donustur as $en => $tr){
			$z = str_replace($en, $tr, $z);
		}
		if(strpos($z, 'Mayıs') !== false && strpos($f, 'F') === false) $z = str_replace('Mayıs', 'May', $z);
		return $z;
	}
