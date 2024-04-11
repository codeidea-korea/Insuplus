CREATE TABLE AceMTcounter_browser (
  cb_browse varchar(255) NOT NULL default '',
  cb_hit int(11) NOT NULL default '0',
  cb_uptime int(11) NOT NULL default '0',
  PRIMARY KEY  (cb_browse)
) TYPE=MyISAM;

CREATE TABLE AceMTcounter_display (
  cd_width smallint(6) NOT NULL default '0',
  cd_height smallint(6) NOT NULL default '0',
  cd_hit int(11) NOT NULL default '0',
  cd_uptime int(11) NOT NULL default '0'
) TYPE=MyISAM;

CREATE TABLE AceMTcounter_ip (
  ci_ip varchar(15) NOT NULL default '',
  ci_domain varchar(100) NOT NULL default '',
  ci_yy tinyint(2) unsigned zerofill NOT NULL default '00',
  ci_mm tinyint(2) unsigned zerofill NOT NULL default '00',
  ci_dd tinyint(2) unsigned zerofill NOT NULL default '00',
  ci_ww tinyint(4) NOT NULL default '0',
  ci_hh tinyint(2) unsigned zerofill NOT NULL default '00',
  ci_hit int(11) NOT NULL default '0',
  ci_todayip int(11) NOT NULL default '0',
  ci_uptime int(11) NOT NULL default '0',
  KEY ci_ip (ci_ip),
  KEY ci_yy (ci_yy),
  KEY ci_mm (ci_mm),
  KEY ci_dd (ci_dd),
  KEY ci_hh (ci_hh)
) TYPE=MyISAM;

CREATE TABLE AceMTcounter_now (
  session_id varchar(50) NOT NULL default '',
  ip varchar(15) NOT NULL default '',
  uptime int(11) default NULL,
  PRIMARY KEY  (session_id)
) TYPE=MyISAM;

CREATE TABLE AceMTcounter_url (
  cu_url text NOT NULL,
  cu_hit int(11) NOT NULL default '0',
  cu_uptime int(11) NOT NULL default '0'
) TYPE=MyISAM;

CREATE TABLE AceMTcounter_site (
  cs_site varchar(255) NOT NULL default '',
  cs_hit int(11) NOT NULL default '0',
  cs_uptime int(11) NOT NULL default '0',
  PRIMARY KEY  (cs_site),
  KEY cs_site (cs_site),
  KEY cs_uptime (cs_uptime)
) TYPE=MyISAM;