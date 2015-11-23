CREATE TABLE IF NOT EXISTS `edukey_todo` (
    `id` int(10) not null auto_increment,
    `title` varchar(100) not null default '',
    `position` int(10) not null default '0',
    `soft_del` smallint(1) not null default '0' comment 'future feature soft del!',
    primary key (id)
)ENGINE=innodb;