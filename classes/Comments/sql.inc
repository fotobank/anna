CREATE TABLE gb_posts (
  id mediumint(8) unsigned NOT NULL auto_increment,
  poster varchar(50) NOT NULL default '',
  email varchar(255) NOT NULL default '',
  text text NOT NULL,
  ip varchar(15) NOT NULL default '',
  create_time int(11) NOT NULL default '0',
  flag tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY flag (flag)
) TYPE=MyISAM;

CREATE TABLE gb_replies (
  id smallint(8) unsigned NOT NULL auto_increment,
  parent mediumint(8) NOT NULL default '0',
  poster mediumint(8) NOT NULL default '0',
  reply text NOT NULL,
  create_time int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY parent (parent)
) TYPE=MyISAM;

CREATE TABLE gb_users (
  id mediumint(8) NOT NULL auto_increment,
  login varchar(30) NOT NULL default '',
  pass varchar(50) NOT NULL default '',
  email varchar(255) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY id (id)
) TYPE=MyISAM;

INSERT INTO gb_users VALUES ('', 'admin', '21232f297a57a5a743894a0e4a801fc3', '');