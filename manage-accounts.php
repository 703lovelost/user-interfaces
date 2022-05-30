<?php
session_start();

/*
* Handles every single operation in admin interface.
*/

class AccountManagement {

  /*
  * checkAction() checks what value $_GET['action'] has and operates the way the value is set.
  */
  function checkAction() {
    $accmgmt = new AccountManagement();

    if($_GET['action'] == 'delete') {
      $accmgmt -> deleteAccount();
    }

    if($_GET['action'] == 'edit') {
      $accmgmt -> editAccount();
    }

    if($_GET['action'] == 'update') {
      $accmgmt -> updateAccount();
    }

    if($_GET['action'] == 'add') {
      $accmgmt -> addAccount();
    }

    // The difference between 'add' and 'insert_account' is that 'add' only generates the form, when 'insert_account' works with values of its fields.
    if ($_GET['action'] == 'insert_account') {
      $accmgmt -> insertAccountIntoDB();
    }
  }

  /*
  * deleteAccount() searches for the necessary user and deletes it.
  */
  function deleteAccount() {
    require 'includes/connect.php';

    $stmt = $pdo -> prepare('SELECT login FROM users WHERE login = ?');
    $stmt -> execute(array($_GET['user']));

  // If the user's not found, the error message will be thrown.
    if ($stmt -> rowCount() == 0) {
      $_SESSION['errorMsg'] = 'The user doesn\'t exist.';
      header('Location: /index.php');
      die();
    }
    else {
      $stmt = $pdo -> prepare('DELETE FROM users WHERE login = ?');
      $stmt -> execute(array($_GET['user']));

      $_SESSION['errorMsg'] = 'The user was deleted.';
      header('Location: /index.php');
      die();
    }
  }

  /*
  * editAccount() searches for a specific user and generates the form using the values from user's row in database.
  */
  function editAccount() {
    require 'includes/connect.php';
    require 'includes/db-arrays-fetch.php';
    require 'includes/header.php';

  // It's better to save $_GET['user'] into special variable, at least because it makes the code more clear.
    $user_to_edit = $_GET['user'];

  // Searching for an user in advance, so we can put his information in form fields.
    $stmt = $pdo -> prepare('SELECT role_id, login, first_name, last_name, sex, birth_date FROM users WHERE login = ?');
    $stmt -> execute(array($user_to_edit));

  // If there is no match in database, the error message will be thrown.
    if ($stmt -> rowCount() == 0) {
      $_SESSION['errorMsg'] = 'The user doesn\'t exist.';
      header('Location: /index.php');
      die();
    }
    else {
      $user_row = $stmt -> fetch();

  // These variables relates to the functions located in db-arrays-fetch.php and contain the arrays of existing roles and sexes.
      $roles = (new FetchArray) -> generateRolesArray();
      $sexes = (new FetchArray) -> generateSexesArray();

  // Shows what user we edit.
      echo 'Update profile ' . $user_row['login'];

      echo '
      <div>
      <form action="/manage-accounts.php?action=update&user=' . $user_to_edit . '" method="post">
        <p>
        <label for="role">Role</label>
        <select name="role">';

      foreach ($roles as $role_key => $role_value)
        {
  // The role is selected if it matches the name from the $roles array, so there will be no accidental changes.
          if ($role_key == $user_row['role_id']) {
            echo '<option value="' . $role_value . '" selected>' . $role_value . '</option>';
          }
          else echo '<option value="' . $role_value . '">' . $role_value . '</option>';
        }
  // All values from query execution are put in fields.
      echo '
        </select></p>
        <p>
        <label for="login">Login</label>
        <input type="text" name="login" class="" value="' . $user_row['login'] . '" required="required" placeholder="Please enter login"></p>
        <p>
        <label for="first_name">First name</label>
        <input type="text" name="first_name" class="" value="' . $user_row['first_name'] . '" required="required" placeholder="Please enter first name"></p>
        <p>
        <label for="last_name">Last name</label>
        <input type="text" name="last_name" class="" value="' . $user_row['last_name'] . '" required="required" placeholder="Please enter last name"></p>
        <p>
        <label for="sex">Sex</label>
        <select name="sex">';
  // Works the same way as role selection.
      foreach ($sexes as $key => $value)
        {
          if ($value == $user_row['sex']) {
            echo '<option value="' . $value . '" selected>' . $value . '</option>';
          }
          else echo '<option value="' . $value . '">' . $value . '</option>';
        }

  // IMPORTANT: 'Password' and 'Confirm password' fields are blank. It's safe to leave them blank while editing: the password value won't change.
      echo '
        </select></p>
        <p>
        <label for="birth_date">Birth date</label>
        <input type="date" name="birth_date" class="" required="required" value="' . $user_row['birth_date'] . '"></p>
        <p>
        <label for="psw">Password</label>
        <input type="password" name="psw" class="" placeholder="Enter your password"></p>
        <p>
        <label for="confirm_psw">Confirm password</label>
        <input type="password" name="confirm_psw" class="" placeholder="Confirm your password"></p>
        <p><input type="submit" value="Edit account"></p>
      </form>
      <div>';
    }
  }

  /*
  * updateAccount() takes POST variables from editing form and sends their values to the database.
  */
  function updateAccount() {
    require 'includes/connect.php';

  // It remains the same, as it's brought by editAccount() function.
    $user_to_edit = $_GET['user'];

    $login = $_POST['login'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $sex = $_POST['sex'];
    $birth_date = $_POST['birth_date'];

  /*
  * First of all, it's important to check if 'Password' and 'Confirm password' fields match.
  * We check this way if user has written something in at least one of the fields.
  */
    if ($_POST['psw'] != $_POST['confirm_psw']) {
      $_SESSION['errorMsg'] = 'The inputs of password and its confirmation are different. <br>Make sure the Caps Lock key is off and try it again.';
      header('Location: /manage-accounts.php?action=edit&user=' . $user_to_edit .'');
      die();
    }
    // If 'Password' field is blank ('Confirm...' one is checked by the previous condition), then the query is executed without changing the password.
      else if ($_POST['psw'] == null) {
        $stmt = $pdo -> prepare('UPDATE users
          SET login = ?, first_name = ?, last_name = ?, sex = ?, birth_date = ?
          WHERE login = ?');
        $stmt -> execute(array($login, $first_name, $last_name, $sex, $birth_date, $user_to_edit));
        $_SESSION['errorMsg'] = 'The user was updated.';
        header('Location: /index.php');
        die();
      }
    // If 'Password' field is changed and properly confirmed, then the query is executed, including password changes.
      else {
        $stmt = $pdo -> prepare('UPDATE users
          SET login = ?, first_name = ?, last_name = ?, sex = ?, birth_date = ?, password = ?
          WHERE login = ?');
    // It's unsafe to store hashed passwords in variables, so they will be hashed throughout the query generating.
        $stmt -> execute(array($login, $first_name, $last_name, $sex, $birth_date, password_hash($_POST['psw'], PASSWORD_DEFAULT), $user_to_edit));
        $_SESSION['errorMsg'] = 'The user was updated.';
        header('Location: /index.php');
        die();
      }
    }

    /*
    * addAccount() generates the form, quite similar to the sign-up form, but it has more options to choose.
    * Then the function sends the form to insertAccountIntoDB().
    */
    function addAccount() {
      require 'includes/connect.php';
      require 'includes/db-arrays-fetch.php';
      require 'includes/header.php';


  // These variables relates to the functions located in db-arrays-fetch.php and contain the arrays of existing roles and sexes.
      $roles = (new FetchArray) -> generateRolesArray();
      $sexes = (new FetchArray) -> generateSexesArray();

      echo '<div>Add new profile</div>';

      echo '
      <div>
      <form action="/manage-accounts.php?action=insert_account" method="post">
        <p>
        <label for="role">Role</label>
        <select name="role">';

    // Cycle of loading array's values into <option> tags.
      foreach ($roles as $role_key => $role_value)
        {
          echo '<option value="' . $role_value . '">' . $role_value . '</option>';
        }

      echo '
        </select></p>
        <p>
        <label for="login">Login</label>
        <input type="text" name="login" class="" required="required" placeholder="Please enter login"></p>
        <p>
        <label for="first_name">First name</label>
        <input type="text" name="first_name" class="" required="required" placeholder="Please enter first name"></p>
        <p>
        <label for="last_name">Last name</label>
        <input type="text" name="last_name" class="" required="required" placeholder="Please enter last name"></p>
        <p>
        <label for="sex">Sex</label>
        <select name="sex">';

    // The same as on manage-accounts.php:218
      foreach ($sexes as $key => $value)
        {
          echo '<option value="' . $value . '">' . $value . '</option>';
        }
      echo '
        </select></p>
        <p>
        <label for="birth_date">Birth date</label>
        <input type="date" name="birth_date" class="" required="required"></p>
        <p>
        <label for="psw">Password</label>
        <input type="password" name="psw" class="" placeholder="Enter your password"></p>
        <p>
        <label for="confirm_psw">Confirm password</label>
        <input type="password" name="confirm_psw" class="" placeholder="Confirm your password"></p>
        <p><input type="submit" value="Create account"></p>
      </form>
      </div>';
    }

    /*
    * insertAccountIntoDB() takes the POST variables from addAccount() and sends them in database.
    */
    function insertAccountIntoDB() {
      require 'includes/connect.php';

      $login = $_POST['login'];
      $first_name = $_POST['first_name'];
      $last_name = $_POST['last_name'];
      $sex = $_POST['sex'];
      $birth_date = $_POST['birth_date'];

    /*
    * Quickly reminder: role_id stores id's of role, not its actual name.
    * So the role value that came from addAccount() changes to its id before making a query.
    */
      $stmt = $pdo -> prepare('SELECT id FROM roles WHERE role_name = ?');
      $stmt -> execute(array($_POST['role']));
      $row = $stmt -> fetch();
      $role_id = $row['id'];

    // If 'Password' and 'Confirm password' fields don't match, the error message will be thrown.
      if ($_POST['psw'] != $_POST['confirm_psw']) {
        $_SESSION['errorMsg'] = 'The inputs of password and its confirmation are different. <br>Make sure the Caps Lock key is off and try it again.';
        header('Location: /manage-accounts.php?action=add');
        die();
      }
      else {
        $stmt = $pdo -> prepare('SELECT login FROM users WHERE login = ?');
        $stmt -> execute(array($_POST['login']));

        // If there's a user found with offered login, the error message will be thrown.
        if ($stmt -> rowCount() != 0) {
          $_SESSION['errorMsg'] = 'Account with this login already exists.';
          header('Location: /manage-accounts.php?action=add');
          die();
        }
        else {
          $stmt = $pdo -> prepare(
            'INSERT INTO users (role_id, login, password, first_name, last_name, sex, birth_date)
            VALUES (?, ?, ?, ?, ?, ?, ?)');
          $stmt -> execute(array($role_id, $login, password_hash($_POST['psw'], PASSWORD_DEFAULT), $first_name, $last_name, $sex, $birth_date));

          $_SESSION['errorMsg'] = 'The account was created.';
          header('Location: /index.php');
          die();
        }
      }
    }
}



(new AccountManagement) -> checkAction();
