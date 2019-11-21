<?php
include_once('database.php');
try 
{
    $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->exec("CREATE DATABASE IF NOT EXISTS `db_camagru`;
                CREATE TABLE  IF NOT EXISTS `db_camagru`.`users`(
	                `user_id` int NOT NULL AUTO_INCREMENT,
	                `username` varchar(13) NOT NULL,
	                `passwd` varchar(128) NOT NULL,
                    `email` varchar(128) NOT NULL,
                    `e_notif` int DEFAULT 1,
                    `active` int DEFAULT 0,
	                PRIMARY KEY (`user_id`)
                );
                CREATE TABLE  IF NOT EXISTS `db_camagru`.`images`(
	                `img_id` int NOT NULL AUTO_INCREMENT,
                    `user_id` int NOT NULL,
	                PRIMARY KEY (`img_id`)
                );
                CREATE TABLE  IF NOT EXISTS `db_camagru`.`comments`(
                    `comment_id` int NOT NULL AUTO_INCREMENT,
	                `img_id` int NOT NULL,
                    `poster_id` int NOT NULL,
                    `content` varchar(42) NOT NULL,
	                PRIMARY KEY (`comment_id`)
                );
                CREATE TABLE  IF NOT EXISTS `db_camagru`.`likes`(
                    `like_id` int NOT NULL AUTO_INCREMENT,
	                `img_id` int NOT NULL,
                    `liker_id` int NOT NULL,
                    PRIMARY KEY (`like_id`)
                );
    ");
}
catch (PDOException $e)
{
    echo "Connection failed: ".$e->getMessage();
}
?>