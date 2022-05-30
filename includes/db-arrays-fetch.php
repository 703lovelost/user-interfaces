<?php
/*
* There are more values possible in 'roles' and 'sexes' (Currently there are two in both cases).
* To add more flexibility to work with these fields, two additional tables were made in database. They are called after them.
* There was also a necessity to add them in form's <select> tags automatically, not manually.
* That's why this file was made for.
*/

class FetchArray {

  /*
  * generateRolesArray() selects roles' names from self-titled table in database and add them into one array.
  */
  function generateRolesArray() {
    require 'includes/connect.php';

    $stmt = $pdo -> prepare('SELECT role_name FROM roles');
    $stmt -> execute();
    $roles_array = $stmt -> fetchAll();
    $roles_count = 1;

    /*
    * Every SELECT fetch returns the array with arrays in it.
    * For example,
    * $users_array = array( [0] => array( ['role_id'] => '1', ['login'] => 'name1' ),
    *                       [1] => array( ['role_id'] => '2', ['login'] => 'name2' ),
    *                       [2] => array( ['role_id'] => '1', ['login'] => 'name3' ) and so on...
    * The goal here is to reduce this contruction to one array.
    */
    foreach ($roles_array as $main_roles_row => $inner_roles_array) {
      foreach ($inner_roles_array as $inner_roles_row => $roles_value)
        $roles[$roles_count] = $roles_value;
      $roles_count++;
    }

    // Array of roles is ready to use.
    return $roles;
  }

  function generateSexesArray() {
    require 'includes/connect.php';

    $stmt = $pdo -> prepare('SELECT name FROM sexes');
    $stmt -> execute();
    $sexes_array = $stmt -> fetchAll();
    $roles_count = 1;

  // The same as im db-arrays-fetch.php:22
    foreach ($sexes_array as $main_sexes_row => $inner_sexes_array) {
      foreach ($inner_sexes_array as $inner_sexes_row => $sexes_value)
        $sexes[$roles_count] = $sexes_value;
      $roles_count++;
    }

    // Array of sexes is ready to use.
    return $sexes;
  }
}
