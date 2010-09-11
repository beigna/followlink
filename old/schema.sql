CREATE TABLE link (
  id char(8) NOT NULL,
  user_id int(10) unsigned NOT NULL,
  url varchar(512) NOT NULL,
  file_name varchar(64) NOT NULL,
  content_type varchar(32) NOT NULL,
  file_size int(10) unsigned NOT NULL,
  hits int(10) unsigned NOT NULL,
  last_hit datetime NOT NULL,
  created_at datetime NOT NULL,
  updated_at datetime NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
