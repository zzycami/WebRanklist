<?php

define("CONFIG_ROOT", "./config/config.ini");
define("DEBUG", TRUE);
define("SEPERATOR", ",");
define("TEAM_SEPERATOR", "|");

define("DB_TABLES", "webranklist_table");


if(file_exists("./library/regex.php")){
	include_once './library/regex.php';
}else {
	die("<center><font color='red'>File regex.php can't find!</font></center>");
}

if(file_exists("./library/files.php")){
	include_once './library/files.php';
}else {
	die("<center><font color='red'>File regex.php can't find!</font></center>");
}

if(file_exists("./library/mysql.php")){
	include_once './library/mysql.php';
}else{
	die("<center><font color='red'>File mysql.php can't find!</font></center>");
}

if(file_exists("./library/dbtable_tables.php")){
	include_once './library/dbtable_tables.php';
}else{
	die("<center><font color='red'>File mysql.php can't find!</font></center>");
}

if(file_exists("./library/xml.php")){
	include_once './library/xml.php';
}else{
	die("<center><font color='red'>File xml.php can't find!</font></center>");
}

$set = getSetting(CONFIG_ROOT);
session_start();