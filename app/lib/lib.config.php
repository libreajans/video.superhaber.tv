<?php
	if(!defined('APP')) die('...');

	//site DEBUG modunda detaylı hata geri bildirileri verir
	//0 debug modu kapalı
	//1 debug modu acık: sql sorguları + tema dosyaları
	//2 debug modu acık: sql sorguları + tema dosyaları + php hataları
	define('ST_DEBUG', 			1);

	//site yayında ise yayın veritabanını kullanır
	//0 local veritabanını kullan
	//1 yayındaki veritabanını kullan demektir
	//dikkat, yayındaki veritabanını lokalden kullanıyorsanız
	//beklenmedik hatalar ile karşılaşabilirsiniz
	define('ST_ONLINE', 		0);

	//site CDN hizmeti kullanıyorsa, CDN hizmetini açıp kapatmak için bu ayarı kullanıyoruz
	//0 CDN kapalı
	//1 CDN acık
	define('ST_CDN', 			0);

	//giriş çıkış işlemleri sırasında
	//captha/görsel doğrulama kullanılıp kullanılmayacağını belirler
	//0 kapalı
	//1 açık demektir
	define('RC_Captcha', 		0);

	//Akismet WP temelli bir yapı olup yorumlardaki spamları denetler
	//0 kapalı
	//1 açık demektir
	define('RC_Akismet',		1);

	//Uygulama varsa, onunla ilintili kısımları gösteriyoruz
	//0 kapalı
	//1 açık demektir
	define('RC_Application',	1);

	//Detaylı SEO için Meta Değerlerini düzenleme özelliğini aktif eder veya kapatır
	//0 kapalı
	//1 açık demektir
	define('RC_DetailedSeo',	1);

	//Sistemde Imagemagick yüklüyse gücünden faydalanalım
	//0 kapalı
	//1 açık demektir
	define('RC_Imagemagick',	1);

	//Hangi Temayı Kullanalım
	//Default
	//Custom (Varsa)
	define('ST_TEMPLATE', 		'default');

	//Daha iyi bir ana sayfa açılışı için Ana Sayfayı HTML olarak daima cachler
	//memcached kurulu olmalıdır veya uygun bir cache server ayarlanmalıdır
	//0 kapalı
	//1 açık demektir
	define('RC_SuperCache',		0);

	//MEMCACHE kullanılsın mı?
	//
	//normalde define ataması yapıyorduk;
	//lakin iki defa define tanımlanamayacağı için
	//değişken olarak atamama yapıyoruz
	//0 pasif, 1 aktif demek oluyor
	$memcache_status			= 1;

	//Mobil Template yönlendirmesi yapılsın mı?
	//
	//normalde define ataması yapıyorduk;
	//lakin iki defa define tanımlanamayacağı için
	//değişken olarak atamama yapıyoruz
	//0 pasif, 1 aktif demek oluyor
	$checmobile					= 1;

