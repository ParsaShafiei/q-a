<?php

namespace Database;


class CreateDB extends Database{
    private $queries = [
        "CREATE TABLE `categories` (`id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(250) COLLATE utf8_persian_ci NOT NULL, PRIMARY KEY (`id`))",
        "CREATE TABLE `users` (`id` int(11) NOT NULL AUTO_INCREMENT, `email` varchar(250) COLLATE utf8_persian_ci NOT NULL, PRIMARY KEY (`id`))"
    ];

    public function run(){
        foreach($this->queries as $query){
            $this->createTable($query);
        }
    }
}