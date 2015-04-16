create database bookmarks;

use bookmarks;

create table user (
    username varchar(30) not null primary key,
    passwd char(42) not null,
    email char(100) not null
);

create table bookmark (
    username varchar(30) not null,
    bm_URL varchar(256) not null,
    index(username),
    index(bm_URL),
    primary key(username, bm_URL) 
);

grant select, insert, delete, update
    on bookmarks.*
    to 'kenneth' identified by 'kenneth';
