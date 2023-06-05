

#曲目表
CREATE TABLE IF NOT EXISTS `album` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL COMMENT '专辑名称',
  `year_release` year(4) DEFAULT NULL COMMENT '专辑发表年代',
  `genre_id` int(11) DEFAULT NULL,
  `picture_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_genre_idx` (`genre_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=146 ;

#专辑列表
CREATE TABLE IF NOT EXISTS `artist` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `description` text,
  `country` varchar(45) DEFAULT NULL,
  `year_formed` year(4) DEFAULT NULL,
  `genre_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `FK_artist_genre_idx` (`genre_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=316 ;

#专辑唱片表
CREATE TABLE IF NOT EXISTS `artist_album` (
  `album_id` int(11) unsigned NOT NULL,
  `artist_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`album_id`,`artist_id`),
  KEY `FK_artist_album` (`artist_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `genre` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `description` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

#音乐库主表
CREATE TABLE `music` (
 `music_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 `name` varchar(200) NOT NULL COMMENT '歌名',
 `singer` varchar(50) NOT NULL COMMENT '歌手',
 `album` varchar(25) NOT NULL COMMENT '专辑（rock,xiha,rap...）',
 `qiniu_url` varchar(200) NOT NULL COMMENT '七牛云播放链接',
 `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
 PRIMARY KEY (`music_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='音乐库主表'

#用户播放列表 playlist
CREATE TABLE `playlist` (
 `playlist_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 `user_id` int(10) NOT NULL COMMENT '用户ID', 
 `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
 PRIMARY KEY (`playlist_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='音乐库主表'