CREATE TABLE `tl_news4ward` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `jumpTo` int(10) unsigned NOT NULL default '0',
  `allowComments` char(1) NOT NULL default '',
  `notify` varchar(32) NOT NULL default '',
  `protected` char(1) NOT NULL default '',
  `groups` blob NULL,
  `categories` blob NULL,
  `makeFeed` char(1) NOT NULL default '',
  `format` varchar(32) NOT NULL default '',
  `language` varchar(32) NOT NULL default '',
  `source` varchar(32) NOT NULL default '',
  `maxItems` smallint(5) unsigned NOT NULL default '0',
  `feedBase` varchar(255) NOT NULL default '',
  `alias` varbinary(128) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `tl_news4ward_article` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `sorting` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `category` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `status` varchar(128) NOT NULL default '',
  `date` int(10) unsigned NOT NULL default '0',
  `time` int(10) unsigned NOT NULL default '0',
  `alias` varbinary(128) NOT NULL default '',
  `author` int(10) unsigned NOT NULL default '0',
  `keywords` text NULL,
  `highlight` char(1) NOT NULL default '',
  `teaserCssID` varchar(255) NOT NULL default '',
  `teaser` text NULL,
  `printable` varchar(255) NOT NULL default '',
  `cssID` varchar(255) NOT NULL default '',
  `space` varchar(64) NOT NULL default '',
  `published` char(1) NOT NULL default '',
  `start` varchar(10) NOT NULL default '',
  `stop` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
  KEY `alias` (`alias`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

