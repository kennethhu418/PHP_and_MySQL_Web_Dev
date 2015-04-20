create database Bookmark;

use Bookmark;

create table User (
    username varchar(30) not null primary key,
    pwd char(42) not null,
    email char(100) not null,
    unique key (email),
    index(email)
);

create table Bookmark (
    bookmarkid int(64) not null auto_increment,
    username varchar(30) not null,
    bookmarkname varchar(100) not null,
    urlID int(64) unsigned not null,
    visitfreq int(64) unsigned not null,

    index(username),
    index(bookmarkname),
    index(urlID),
    primary key(bookmarkid) 
);

create table URL (
    urlID int(64) unsigned auto_increment not null,
    url varchar(250) not null primary key,
    visitfreq int(64) unsigned not null,
    index(urlID)
);

grant select, insert, delete, update
    on Bookmark.*
    to 'kenneth' identified by 'kenneth';
