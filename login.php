<?php
  session_start();
  require_once 'includes/auth.php';
  require_once 'index.php';
  require_once 'includes/header.php';

  /*
  * Loading authentication and registration forms.
  */

  class Authentication {

    /*
    * Function checkAuth() checks what form should be loaded, depending on the GET variable.
    * There is no preventing logged-in users from moving to log-in page necessary, as they will be redirected to their available interfaces anyway.
    */

    function checkAuth() {
        if ($_GET['signup']) {
          (new Authentication) -> createSignUpForm();
        }
        else {
          (new Authentication) -> createLogInForm();
        }
    }

    /*
    * Function createLogInForm() loads the form for authentication. Link to the registration form is available.
    * After submitting the values go to includes/auth.php for processing.
    */

    function createLogInForm() {
      echo '
      <div>
        <form action="includes/auth.php?action=login" method="post">
          <p>
          <label for="login">Login</label>
          <input type="text" name="login" class="" required="required" placeholder="Enter your login"></p>
          <p>
          <label for="psw">Password</label>
          <input type="password" name="psw" class="" required="required" placeholder="Enter your password"></p>
          <p><input type="submit" value="Log in"></p>
          </form>
          <p>
            Are you new here? <a href="login.php?signup=1">Make your own account!</a>
          </p>
      </div>
      ';
    }

    /*
    * Function createLogInForm() loads the form for registration. Link to the authentication form is available as well.
    * Submitting works the same way.
    */

    function createSignUpForm() {
      require 'includes/db-arrays-fetch.php';

      // $sexes contains the array with values of database's table 'sexes' to load them in <option> tags.
      $sexes = (new FetchArray) -> generateSexesArray();

      echo '
      <div>
        <form action="includes/auth.php?action=signup" method="post">
          <p>
          <label for="login">Login</label>
          <input type="text" name="login" class="" required="required" placeholder="Enter your login"></p>
          <p>
          <label for="first_name">First name</label>
          <input type="text" name="first_name" class="" required="required" placeholder="Enter your first name"></p>
          <p>
          <label for="last_name">Last name</label>
          <input type="text" name="last_name" class="" required="required" placeholder="Enter your last name"></p>
          <p>
          <label for="sex">Sex</label>
          <select name="sex">';

      // Cycle of loading array's values into <option> tags.
        foreach ($sexes as $key => $value)
          {
            echo '<option value="' . $value . '">' . $value . '</option>';
          }
        echo '
          </select></p>
          <p>
          <label for="birth_date">Birth date</label>
          <input type="date" name="birth_date" class="" required="required" value="' . date("Y-m-d") . '"></p>
          <p>
          <label for="psw">Password</label>
          <input type="password" name="psw" class="" required="required" placeholder="Enter your password"></p>
          <p>
          <label for="confirm_psw">Confirm password</label>
          <input type="password" name="confirm_psw" class="" required="required" placeholder="Confirm your password"></p>
          <p><input type="submit" value="Sign up"></p>
          </form>
          <p>
            Have an account? <a href="login.php">Log in right now!</a>
          </p>
        </div>
      ';
    }
  }
