<?php
  session_start();

  /*
  * Takes the values from authentication and registration forms (login.php) and works with them.
  */

  class Authorization {

    /*
    * checkAuthFormAction() checks the GET variable 'action' to identify what form had come and passes it to appropriate functions.
    */

    function checkAuthFormAction() {
      if ($_GET['action'] == 'login') {
        (new Authorization) -> authUser();
      }
      if ($_GET['action'] == 'signup') {
        (new Authorization) -> signUserUp();
      }
    }

    /*
    * authUser() works with authentication forms.
    * It checks for mistakes during inputting and loads the interface to user.
    */

    function authUser() {
      require_once 'connect.php';

      $stmt = $pdo -> prepare('SELECT login, first_name, last_name, role_id, password FROM users WHERE login = ?');
      $stmt -> execute(array($_POST['login']));

      //If there are no matches in database, the message wil be thrown.
      if ($stmt -> rowCount() == 0) {
        $_SESSION['errorMsg'] = 'Invalid login or password.';
        header('Location: /index.php');
        die();
      }
      else {
        $row = $stmt -> fetch();

      /*
      * The passwords are stored already hashed in database.
      * password_verify($password, $hashedpassword) is used to compare password to its hashed variant.
      * If they don't match, the message will be thrown.
      */
        if (password_verify($_POST['psw'], $row['password']) == true) {
          $first_name = $row['first_name'];
          $last_name = $row['last_name'];

      // Some interface will be loaded depending on what status/role is set in database.
          if ($row['role_id'] == '1') {
            $_SESSION['user_status'] = 'admin';
            $_SESSION['page_title'] = 'Admin Interface';
            // $_SESSION['name'] is showing in the interface after it's initiated, containing first and last names of user.
            $_SESSION['name'] = $first_name . ' ' . $last_name;
            header('Location: ../index.php');
            die();
          }
          if ($row['role_id'] == '2') {
            $_SESSION['user_status'] = 'client';
            $_SESSION['page_title'] = 'Client Interface';
            $_SESSION['name'] = $first_name . ' ' . $last_name;
            header('Location: ../index.php');
            die();
          }
        }
        else {
          $_SESSION['errorMsg'] = 'Invalid login or password.';
          header('Location: /index.php');
          die();
        }
      }
    }

    /*
    * signUserUp() works with registration forms.
    * It checks for mistakes during inputting, send the query with user's information to database and loads the interface.
    * IMPORTANT: You can only make users without admin privileges this way, to prevent admin interface to be available to everyone.
    */

    function signUserUp() {
      require_once 'connect.php';

      $login = $_POST['login'];
      $first_name = $_POST['first_name'];
      $last_name = $_POST['last_name'];
      $sex = $_POST['sex'];
      $birth_date = $_POST['birth_date'];

      // If fields "Password" and "Confirm password" don't match, the message will be thrown.
      if ($_POST['psw'] != $_POST['confirm_psw']) {
        $_SESSION['errorMsg'] = 'The inputs of password and its confirmation are different. <br>Make sure the Caps Lock key is off and try it again.';
        header('Location: ../login.php?signup=1');
        die();
      }
      else {
        $stmt = $pdo -> prepare('SELECT login FROM users WHERE login = ?');
        $stmt -> execute(array($_POST['login']));

        // If there's already the user with this login, the message will be thrown.
        if ($stmt -> rowCount() != 0) {
          $_SESSION['errorMsg'] = 'Account with this login already exists. Try another one.';
          header('Location: ../login.php?signup=1');
          die();
        }
        else {
          // Prevention of making admin accounts is here: role_id always equals 2 (role 'client').
          $stmt = $pdo -> prepare(
            'INSERT INTO users (role_id, login, password, first_name, last_name, sex, birth_date)
            VALUES (2, ?, ?, ?, ?, ?, ?)');
          $stmt -> execute(array($login, password_hash($_POST['psw'], PASSWORD_DEFAULT), $first_name, $last_name, $sex, $birth_date));
          $_SESSION['user_status'] = 'client';
          $_SESSION['page_title'] = 'Client Interface';
          $_SESSION['name'] = $first_name . ' ' . $last_name;
          header('Location: /client_interface.php');
          die();
        }
      }
    }
  }

  (new Authorization) -> checkAuthFormAction();
