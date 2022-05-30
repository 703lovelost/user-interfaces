<?php
  /*
  * Creates a connection to the database using PDO class.
  * IMPORTANT: Combination of login 'root' and password 'root' is unsafe to use in commercial development.
  * It makes the database more vulnerable.
  * These values are set for making the project easier to install and a comfortable presentation.
  */

  $host = 'localhost';
  $db   = 'userinterfaces_db';
  $user = 'root';
  $pass = 'root';
  $charset = 'utf8';

  $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

  /*
  * PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION lets PDO class throw exceptions if something's wrong with query.
  * PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC is set to return arrays indexed by column name.
  * PDO::ATTR_EMULATE_PREPARES => false is set to let MySQL prepare the queries instead of PDO.
  */
  $opt = [
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES   => false,
  ];

  $pdo = new PDO($dsn, $user, $pass, $opt);
