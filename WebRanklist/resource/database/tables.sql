create table  if not exists webranklist_table(
tableid int(11) not null,
title varchar(500) not null,
url varchar(500) not null,
settime int(11) not null,
encoding varchar(500) not null,
root varchar(500) not null,
primary key (tableid)
);