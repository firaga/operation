CREATE TABLE `sq_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL DEFAULT '0',
  `type` varchar(20) NOT NULL DEFAULT '0',
  `full_screen` tinyint(4) NOT NULL DEFAULT '0',
  `visit_ids` varchar(500) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `url` (`url`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;

-- 菜单树
CREATE TABLE `sq_menu_tree` (
  `id` int(11) unsigned NOT NULL DEFAULT '0',
  `pid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE opr_user(
  id int NOT NULL AUTO_INCREMENT,
  username VARCHAR(20) NOT NULL DEFAULT '',
  passwd int NOT NULL DEFAULT 0,
  salt int NOT NULL DEFAULT 0,
  flag tinyint NOT NULL DEFAULT 0,
  add_uid int NOT NULL DEFAULT 0,
  ctime timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  mtime TIMESTAMP NOT NULL  DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE  TABLE opr_authority(
  id int NOT NULL AUTO_INCREMENT,
  module VARCHAR (50) NOT NULL DEFAULT '',
  name VARCHAR (50) NOT NULL DEFAULT '',
  readable tinyint DEFAULT 0 NOT NULL ,
  writeable tinyint DEFAULT 0 NOT NULL
) ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE  TABLE opr_user_authority(
  id int NOT NULL AUTO_INCREMENT,
  uid int NOT NULL DEFAULT 0,
  mid int NOT NULL DEFAULT 0
) ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

