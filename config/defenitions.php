<?php
use App\Database;
return [
    Database::class => function () {


        return new Database(host: getenv('DB_HOST'),
                            dbname: getenv('MYSQL_DATABASE'),
                            user: getenv('MYSQL_USER'),
                            password: getenv('MYSQL_PASSWORD'));
    },
];