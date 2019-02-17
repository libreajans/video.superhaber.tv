<?php if(!defined('APP')) die('...'); ?>

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `app_comment` (
`comment_id` int(11) UNSIGNED NOT NULL,
`comment_content` int(11) DEFAULT NULL,
`comment_author` varchar(128) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`comment_ip` varchar(128) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`comment_text` text COLLATE utf8mb4_turkish_ci,
`comment_status` int(1) DEFAULT '0',
`comment_template` int(1) DEFAULT NULL,
`comment_aprover` int(11) DEFAULT '0',
`create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci ROW_FORMAT=DYNAMIC;


CREATE TABLE `app_content` (
`content_id` int(11) UNSIGNED NOT NULL,
`content_title` varchar(256) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`content_text` text COLLATE utf8mb4_turkish_ci,
`content_image` varchar(256) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`content_video` varchar(256) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`content_seo_title` varchar(256) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`content_seo_url` varchar(256) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`content_seo_metadesc` varchar(256) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`content_tags` varchar(256) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`content_type` int(1) DEFAULT NULL,
`content_cat` int(3) DEFAULT '0',
`content_cat2` int(3) DEFAULT NULL,
`content_cat3` int(3) DEFAULT NULL,
`content_status` int(1) DEFAULT '0',
`content_comment_status` int(1) DEFAULT '1',
`content_ads_status` int(1) DEFAULT '1',
`content_google_status` int(1) DEFAULT '0',
`content_user` int(11) DEFAULT '0',
`content_duration` int(11) DEFAULT NULL,
`content_redirect` varchar(32) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`content_image_dir` varchar(11) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`content_url` varchar(320) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`content_image_url` varchar(320) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`content_thumb_url` varchar(320) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`content_video_url` varchar(320) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`content_time` timestamp NULL DEFAULT NULL,
`create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
`change_time` timestamp NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci ROW_FORMAT=DYNAMIC;


CREATE TABLE `app_user` (
`user_id` int(11) NOT NULL,
`user_email` varchar(128) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`user_pass` varchar(128) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`user_name` varchar(128) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`user_realname` varchar(128) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`user_avatar` varchar(128) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`user_auth` text COLLATE utf8mb4_turkish_ci,
`user_status` int(1) DEFAULT NULL,
`create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci ROW_FORMAT=DYNAMIC;

INSERT INTO `app_user` (`user_id`, `user_email`, `user_pass`, `user_name`, `user_realname`, `user_avatar`, `user_auth`, `user_status`, `create_time`) VALUES
(1, 'root@superhaber.tv', '8893e60364947ec883d170a755a0a2a3', 'Root', 'Root', 'avatar5.png', 'a:25:{s:9:\"user_list\";s:1:\"1\";s:8:\"user_add\";s:1:\"1\";s:9:\"user_edit\";s:1:\"1\";s:11:\"user_delete\";s:1:\"1\";s:12:\"content_list\";s:1:\"1\";s:11:\"content_add\";s:1:\"1\";s:12:\"content_edit\";s:1:\"1\";s:14:\"content_delete\";s:1:\"1\";s:12:\"comment_list\";s:1:\"1\";s:11:\"comment_add\";s:1:\"1\";s:12:\"comment_edit\";s:1:\"1\";s:14:\"comment_delete\";s:1:\"1\";s:10:\"stats_list\";s:1:\"1\";s:9:\"stats_add\";s:1:\"1\";s:10:\"stats_edit\";s:1:\"1\";s:12:\"stats_delete\";s:1:\"1\";s:8:\"log_self\";s:1:\"1\";s:10:\"log_others\";s:1:\"1\";s:10:\"log_anonim\";s:1:\"1\";s:13:\"user_truncate\";s:1:\"1\";s:16:\"content_truncate\";s:1:\"1\";s:22:\"content_truncate_image\";s:1:\"1\";s:16:\"comment_truncate\";s:1:\"1\";s:15:\"content_keyword\";s:1:\"1\";s:18:\"content_tags_empty\";s:1:\"1\";}', 9, '2014-11-10 05:58:13'),
(22, 'berkin@superhaber.tv', 'c046d74ee9ce96389a077e33bfdb92db', 'Berkin', 'Berkin', 'avatar16.png', 'a:17:{s:9:\"user_list\";s:1:\"1\";s:8:\"user_add\";s:1:\"1\";s:9:\"user_edit\";s:1:\"1\";s:11:\"user_delete\";s:1:\"1\";s:12:\"content_list\";s:1:\"1\";s:11:\"content_add\";s:1:\"1\";s:12:\"content_edit\";s:1:\"1\";s:14:\"content_delete\";s:1:\"1\";s:12:\"comment_list\";s:1:\"1\";s:11:\"comment_add\";s:1:\"1\";s:12:\"comment_edit\";s:1:\"1\";s:14:\"comment_delete\";s:1:\"1\";s:10:\"stats_list\";s:1:\"1\";s:9:\"stats_add\";s:1:\"1\";s:10:\"stats_edit\";s:1:\"1\";s:12:\"stats_delete\";s:1:\"1\";s:8:\"log_self\";s:1:\"1\";}', 9, '2017-06-07 09:04:35');

CREATE TABLE `app_user_log` (
`id` int(11) NOT NULL,
`user_id` int(11) DEFAULT '0',
`user_ip` varchar(128) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`user_action` varchar(512) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci ROW_FORMAT=DYNAMIC;

CREATE TABLE `app_view` (
`id` int(11) UNSIGNED NOT NULL,
`content_view` int(11) DEFAULT '0',
`content_view_real` int(11) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci ROW_FORMAT=DYNAMIC;

ALTER TABLE `app_comment`
ADD UNIQUE KEY `content_id` (`comment_id`);

ALTER TABLE `app_content`
ADD UNIQUE KEY `content_id` (`content_id`),
ADD KEY `content_status` (`content_status`),
ADD KEY `content_cat` (`content_cat`),
ADD KEY `content_type` (`content_type`),
ADD KEY `content_tags` (`content_tags`(250)),
ADD KEY `content_cat2` (`content_cat2`),
ADD KEY `content_cat3` (`content_cat3`);

ALTER TABLE `app_user`
ADD UNIQUE KEY `user_id` (`user_id`),
ADD UNIQUE KEY `user_email` (`user_email`),
ADD KEY `user_pass` (`user_pass`);

ALTER TABLE `app_user_log`
ADD UNIQUE KEY `id` (`id`),
ADD KEY `user_id` (`user_id`),
ADD KEY `user_ip` (`user_ip`);

ALTER TABLE `app_view`
ADD UNIQUE KEY `id` (`id`) USING BTREE;


ALTER TABLE `app_comment` MODIFY `comment_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `app_content` MODIFY `content_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `app_user` MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
ALTER TABLE `app_user_log` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- partition ekle
ALTER TABLE app_content partition by hash(content_id) partitions 10;
ALTER TABLE app_view partition by hash(id) partitions 10;

-- comment_template kaldır

ALTER TABLE `app_comment` DROP `comment_template`;

-- some stupid idea

ALTER TABLE `app_content` CHANGE `content_seo_metadesc` `content_seo_metadesc` VARCHAR(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NULL DEFAULT NULL;
ALTER TABLE `app_content` CHANGE `content_tags` `content_tags` VARCHAR(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NULL DEFAULT NULL;
