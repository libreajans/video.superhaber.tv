<?php

	class comment
	{

		private function cleanTextComment($comment_text)
		{

			//raw gelen datayı entity edelim
			$comment_text = htmlentities($comment_text);
	// 		echo $comment_text;

			//bölünmez boşlukları kabul ETMEYELİM
			$comment_text = str_replace('&nbsp;',' ',$comment_text);

			//] işareti sonrasında boşluk olmasın
			$comment_text = str_replace('] ',']',$comment_text);

			//] işareti sonrasında boşluk olmasın
			$comment_text = str_replace(']&nbsp;',']',$comment_text);

			//] işareti öncesinde p olmasın
			$comment_text = str_replace('&lt;p&gt;[','[',$comment_text);

			//] işareti sonrasında p olmasın
			$comment_text = str_replace(']&lt;/p&gt;',']',$comment_text);

			//çift br -> p olsun
			$comment_text = str_replace('&lt;br /&gt;&lt;br /&gt;','</p><p>',$comment_text);

			//daha beter olsun, tek br de p olsun
			$comment_text = str_replace('&lt;br /&gt;','</p><p>',$comment_text);

			//daha beter olsun, tek <br> de p olsun
			$comment_text = str_replace('&lt;br&gt;','</p><p>',$comment_text);

			//hatalı paragraf
			$comment_text = str_replace('&lt;p&gt;&lt;/p&gt;','',$comment_text);
			$comment_text = str_replace('&lt;p&gt; &lt;/p&gt;','',$comment_text);

			//hatalı paragraf başına enter koymak
			$comment_text = str_replace('&lt;p&gt;&lt;br /&gt;','<p>',$comment_text);

			//hatalı paragraf sonuna enter koymak
			$comment_text = str_replace('&lt;br /&gt;&lt;p&gt;','</p>',$comment_text);

			//hatalı paragraf başı + boşluk
			$comment_text = str_replace('&lt;p&gt; ','&lt;p&gt;',$comment_text);
			$comment_text = str_replace('&lt;p&gt;&nbsp;','&lt;p&gt;',$comment_text);

			//hatalı br + boşluk
			$comment_text = str_replace('&lt;br /&gt;&nbsp;','&lt;br /&gt;',$comment_text);
			$comment_text = str_replace('&lt;br /&gt; ','&lt;br /&gt;',$comment_text);

			//hatalı h4 başı + boşluk
			$comment_text = str_replace('&lt;h4&gt; ','&lt;h4&gt;',$comment_text);
			$comment_text = str_replace('&lt;h4&gt;&nbsp;','&lt;h4&gt;',$comment_text);

			//hatalı h4 başı + strong + boşluk
			$comment_text = str_replace('&lt;h4&gt;&lt;strong&gt;&nbsp;','&lt;h4&gt;&lt;strong&gt;',$comment_text);
			$comment_text = str_replace('&lt;h4&gt;&lt;strong&gt; ','&lt;h4&gt;&lt;strong&gt;',$comment_text);

			//bu değişimlerin sonuna
			//boşluk eklemek sorun yaratıyor
			//noktalı virgüller
			$comment_text = str_replace(' ;',';',$comment_text);
			//$comment_text = str_replace(';','; ',$comment_text);

			//bu değişimler sorunsuz

			//hatalı üç nokta
			$comment_text = str_replace('......','...',$comment_text);
			$comment_text = str_replace('.....','...',$comment_text);
			$comment_text = str_replace('....','...',$comment_text);
			//malum üç nokta
			$comment_text = str_replace(' ...','...',$comment_text);
			//hatalı üç nokta

			//nokta
			$comment_text = str_replace(' .','.',$comment_text);
			$comment_text = str_replace('.','. ',$comment_text);
			$comment_text = str_replace('. . .','...',$comment_text);

			//virgüller
			$comment_text = str_replace(' ,',',',$comment_text);
			$comment_text = str_replace(',',', ',$comment_text);

			//çift nokta
			$comment_text = str_replace(' :',':',$comment_text);
			$comment_text = str_replace(':',': ',$comment_text);
			//soru işareti
			$comment_text = str_replace('??','?',$comment_text);
			$comment_text = str_replace(' ?','?',$comment_text);
			$comment_text = str_replace('?','? ',$comment_text);

			//ünlem işareti
			$comment_text = str_replace('!!','!',$comment_text);
			$comment_text = str_replace(' !','!',$comment_text);
			$comment_text = str_replace('!','! ',$comment_text);

			//boşlukları 3 defa temizleyelim
			$comment_text = str_replace('  ',' ',$comment_text);
			$comment_text = str_replace('  ',' ',$comment_text);
			$comment_text = str_replace('  ',' ',$comment_text);

			//entitiy gelen datayı geri raw edelim
			$comment_text = html_entity_decode($comment_text);

			return $comment_text;
		}

		private function get_real_ip()
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

		public function __construct()
		{
			$this->conn = $GLOBALS['conn'];
		}

		public function comment_list_content($_id, $json = 0)
		{
			$sql = 'SELECT
						comment_id,
						comment_author,
						comment_text,
						create_time,
						DATE_FORMAT(create_time, "%Y.%m.%d %H:%i") AS publish_time,
						DATE_FORMAT(create_time, "%d.%m.%Y") AS create_time_f
					FROM
						'.T_COMMENTS.'
					WHERE
						comment_status = 1
					AND
						comment_content = '.$_id.'
					ORDER BY
						create_time ASC';
			if(memcached == 0) $list = $this->conn->GetAll($sql);
			if(memcached == 1) $list = $this->conn->CacheGetAll(cachetime, $sql);
			$adet = count($list);
			if($adet > 0)
			{
				if($json == 0) return $list;

				if($json == 1)
				{
					/**
					| Json datayı tablet ve phone uygulamasına gönderirken kullanıyoruz
					| bu sebeple kullanılmayacak kimi değerleri hiç göndermiyoruz
					*/

					for($i = 0; $i < $adet; $i++)
					{
						unset
						(
							$list[$i]['create_time'],
							$list[$i]['create_time_f'],
							$list[$i]['xxx']
						);
					}
					return $list;
				}
			}
			else
			{
				return false;
			}
		}

		public function comment_add_app($_id, $author, $comment)
		{
			$author		= trim($author);
			$comment	= trim($comment);
			if($_id == 0) return false;

			//boş gelen değerler kayıt edilmesin
			if($author <> '' OR $comment <> '')
			{
				//kendi ip mizi kendimiz bulalım
				$ip = $this->get_real_ip();

				$record = array(
					'comment_content'		=> $_id,
					'comment_author'		=> $author,
					'comment_ip' 			=> $ip,
					'comment_text'			=> n2br($comment),
					'comment_status'		=> 2,
					'create_time'			=> date("Y-m-d H:i:s")
				);

				$rs = $this->conn->AutoExecute(T_COMMENTS, $record, 'INSERT');
				if($rs == false)
				{
					throw new Exception($this->conn->ErrorMsg());
				}
				return true;
			}
			return false;
		}

		/**
		| Standart 4 İşlem; Ekle, Düzenle, Sil, Listele
		*/

		public function comment_add($_id)
		{
			global $L;

			foreach($_REQUEST as $k => $v) $_REQUEST[$k] = trim(htmlspecialchars($v));

			$error 				= 0;

			if($_REQUEST['isim'] == '' OR $_REQUEST['yorum'] == '' ) $error = 1;

			//daha önceden bu başlığa yorum yazmışsa yeniden yorum yapamasın
			if($_SESSION[SES]['comment'][$_id] == 1) $error = 1;

			//boş gelen değerler kayıt edilmesin
			if($error == 0)
			{
				$akismet = new Akismet(SITELINK, akismetKey);
				$akismet->setCommentAuthor($_REQUEST['isim']);
				$akismet->setCommentContent($_REQUEST['yorum']);
				$akismet->setUserIP($_SESSION[SES]['ip']);
				$akismet->setPermalink(SITELINK);

				if($akismet->isCommentSpam())
				{
					//store the comment but mark it as spam (in case of a mis-diagnosis)
					//$comment_status = 3;

					//artık spam diye işaretlemiyor, direk siliyoruz
					return false;
				}
				else
				{	//store the comment normally
					$comment_status = 2;
				}

				//yönetici ise yorumları otomatik onaylayalım
				if($_SESSION[SES]['ADMIN'] == 1)
				{
					$comment_status					= 1;
					unset($_SESSION[SES]['comment'][$_id]);
				}
				else
				{
					//commented status için bu değeri oturuma alıyoruz
					//böylece tekrar tekrar yorum yaptırmıyoruz
					$_SESSION[SES]['comment'][$_id] = 1;
				}

				//hata yoksa
				//sonra tekrar kullanmak amacıyla
				//isim ve eposta bilgilerini oturuma alalım
				$_SESSION[SES]['isim'] 		= myReq("isim", 1);

				$record = array(
					'comment_content'		=> $_id,
					'comment_author'		=> $_REQUEST['isim'],
					'comment_text'			=> n2br(strip_tags($_REQUEST['yorum'])),
					'comment_ip'			=> $_SESSION[SES]['ip'],
					'comment_status'		=> $comment_status,
					'create_time'			=> date("Y-m-d H:i:s")
				);

				$rs = $this->conn->AutoExecute(T_COMMENTS, $record, 'INSERT');
				if($rs == false)
				{
					throw new Exception($this->conn->ErrorMsg());
				}
				return true;
			}
			else
			{
				return false;
			}
		}

		public function comment_edit($_id)
		{
			foreach($_REQUEST as $k => $v) $_REQUEST[$k] = trim($v);

			$record = array(
				'comment_content'		=> $_REQUEST['comment_content'],
				'comment_author'		=> $_REQUEST['comment_author'],
				'comment_text'			=> $this->cleanTextComment($_REQUEST['comment_text']),
				'comment_status'		=> $_REQUEST['comment_status'],
				'comment_aprover'		=> $_SESSION[SES]['user_id'],
				'create_time'			=> $_REQUEST['create_time'],
			);

			$rs = $this->conn->AutoExecute(T_COMMENTS, $record, 'UPDATE', 'comment_id='.$_id);
			if($rs == false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}
		}

		public function comment_delete($_id)
		{
			$sql = 'DELETE FROM '.T_COMMENTS.' WHERE comment_id= '.$_id;
			if($this->conn->Execute($sql) === false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}
		}

		public function comment_list_small
		(
			$keyword 	= 'none',
			$time 		= 'none',
			$user 		= 'none',
			$status 	= 'none',
			$limit 		= 30
		)
		{
			if($keyword	<> 'none')	$sql_keyword	= ' AND ( comment_author LIKE "%'.$keyword.'%" OR comment_text LIKE "%'.$keyword.'%" OR comment_ip LIKE "%'.$keyword.'%" )';
			if($time	<> 'none')	$sql_time		= ' AND create_time LIKE "'.$time.'%"';
			if($user <> 'none')		$sql_user 		= ' AND comment_aprover = '.$user;
			if($status <> 'none')	$sql_status 	= ' AND comment_status IN ('.$status.')';

			$sql = 'SELECT
						*,
						DATE_FORMAT(create_time, "%d.%m.%Y %H:%i") AS create_time_f
					FROM
						'.T_COMMENTS.'
					WHERE
						comment_id > 0
						'.$sql_user.'
						'.$sql_status.'
						'.$sql_keyword.'
					ORDER BY
						comment_id DESC
						LIMIT 0,'.$limit;
			return $this->conn->GetAll($sql);
		}

		public function comment_list($_id = 0)
		{
			$sql = 'SELECT
						*,
						DATE_FORMAT(create_time, "%d.%m.%Y") AS create_time_f
					FROM
						'.T_COMMENTS;

			if($_id <> 0)
			{
				$sql = $sql.' WHERE comment_id = '.$_id;
			}
			return $this->conn->GetAll($sql);
		}

		public function comment_truncate()
		{
			$sql = 'SELECT
						comment_id
					FROM
						'.T_COMMENTS.'
					WHERE
						comment_status IN (0,3,4)';
			$rs = $this->conn->GetAll($sql);

			foreach($rs as $k => $v)
			{
				$this->comment_delete($v['comment_id']);
			}
			return true;
		}

		public function get_comment_draft()
		{
			$sql = 'SELECT
						count(comment_id)
					FROM
						'.T_COMMENTS.'
					WHERE
						comment_status = 2';
			return $this->conn->GetOne($sql);
		}

		public function get_comment_count($_id)
		{
			$sql = 'SELECT
						count(comment_id)
					FROM
						'.T_COMMENTS.'
					WHERE
						comment_status = 1
					AND
						comment_content = '.$_id;
			return $this->conn->GetOne($sql);
		}

		public function set_comment_spam($_id)
		{
			$icerik = $this->comment_list($_id);

			$akismet = new Akismet(SITELINK, akismetKey);
			$akismet->setCommentAuthor($icerik[0]['comment_author']);
			$akismet->setCommentContent(strip_tags($icerik[0]['comment_text']));
			$akismet->setUserIP($icerik[0]['comment_author']);
			$akismet->setPermalink(SITELINK);
			$akismet->submitSpam();
		}
	}
