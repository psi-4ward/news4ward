-- ********************************************************
-- *                                                      *
-- * IMPORTANT NOTE                                       *
-- *                                                      *
-- * Do not import this file manually but use the Contao  *
-- * install tool to create and maintain database tables! *
-- *                                                      *
-- ********************************************************

--
-- Table `tl_news4ward_article`
--

CREATE TABLE `tl_news4ward` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `jumpTo` int(10) unsigned NOT NULL default '0',
  `jumpToList` int(10) unsigned NOT NULL default '0',
  `protected` char(1) NOT NULL default '',
  `groups` blob NULL,
  `makeFeed` char(1) NOT NULL default '',
  `format` varchar(32) NOT NULL default '',
  `language` varchar(32) NOT NULL default '',
  `source` varchar(32) NOT NULL default '',
  `maxItems` smallint(5) unsigned NOT NULL default '0',
  `feedBase` varchar(255) NOT NULL default '',
  `alias` varbinary(128) NOT NULL default '',
  `description` text NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table `tl_news4ward_article`
--

CREATE TABLE `tl_news4ward_article` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `sorting` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `status` varchar(128) NOT NULL default '',
  `alias` varbinary(128) NOT NULL default '',
  `author` int(10) unsigned NOT NULL default '0',
  `keywords` text NULL,
  `description` text NULL,
  `highlight` char(1) NOT NULL default '',
  `teaserCssID` varchar(255) NOT NULL default '',
  `teaser` text NULL,
  `teaserImage` varchar(255) NOT NULL default '',
  `social` varchar(255) NOT NULL default '',
  `cssID` varchar(255) NOT NULL default '',
  `published` char(1) NOT NULL default '',
  `start` varchar(10) NOT NULL default '',
  `stop` varchar(10) NOT NULL default '',
  `sticky` char(1) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
  KEY `alias` (`alias`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table `tl_news4ward_tag`
--

CREATE TABLE `tl_news4ward_tag` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `tag` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Table `tl_module`
-- 

CREATE TABLE `tl_module` (
  `news4ward_archives` blob NULL,
  `news4ward_featured` varchar(16) NOT NULL default '',
  `news4ward_numberOfItems` smallint(5) unsigned NOT NULL default '0',
  `news4ward_jumpToCurrent` varchar(16) NOT NULL default '',
  `news4ward_metaFields` varchar(255) NOT NULL default '',
  `news4ward_template` varchar(32) NOT NULL default '',
  `news4ward_format` varchar(32) NOT NULL default '',
  `news4ward_startDay` smallint(5) unsigned NOT NULL default '0'
  `news4ward_order` varchar(32) NOT NULL default '',
  `news4ward_showQuantity` char(1) NOT NULL default '',
  `news4ward_facebookMeta` char(1) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table `tl_user`
--

CREATE TABLE `tl_user` (
  `news4ward` blob NULL,
  `news4ward_newp` blob NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table `tl_user_group`
--

CREATE TABLE `tl_user_group` (
  `news4ward` blob NULL,
  `news4ward_newp` blob NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table `tl_layout`
--

CREATE TABLE `tl_layout` (
  `news4ward_feeds` blob NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;