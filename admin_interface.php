<?php
session_start();

/*
* Loads interface with administrator privileges, such as:
* 1. Watching the list of currently registered users, split into pages;
* 2. Adding new users with different kinds of statuses;
* 3. Editing users;
* 4. Deleting users.
*/

class AdminInterface {

  /*
  * openAdminInterface() loads the welcome text with log out link and initiating showPageCount() function.
  */
  function openAdminInterface() {

    echo '<div>Welcome, ' . $_SESSION['name'] . '.
    <br>
    <a href="/logout.php">Log out</a>, if you need to.
    </div>';

    (new AdminInterface) -> showPageCount($skip_users);
  }

  /*
  * showPageCount() gets the amount of users registered from database, then divides them by 5.
  * After that, it generates the amount of pages out of division result, calculates how much users to skip and sends the value to showUserList().
  */
  function showPageCount() {
    require 'includes/connect.php';

    $stmt = $pdo -> prepare('SELECT id FROM users');
    $stmt -> execute();
    // Every row in table 'users' counts for one user registered.
    $users_count = $stmt -> rowCount();
    echo '<div>There are ' . $users_count . ' users currently registered.</div>';

    // Button to create a new user.
    $add_acc_link = '/manage-accounts.php?action=add';

    echo '
    <div>
       <input type="button" value="Add new user" onclick=location.href="' . $add_acc_link . '">
    </div>';

    // ceil() rounds fractions up, so there would be an additional page for users left if it wasn't the integer value.
    $pages_count = ceil($users_count / 5);

    echo '<div>';

    // If user just opened the interface or cleared the query string, he would see the first page.
    if (isset($_GET['page'])) {
      $page = $_GET['page'];
    }
    else {
      $page = 1;
    }

    // Due to this cycle, every page except the currently opened one will have a link to it.
    for ($i = 1; $i <= $pages_count; $i++)
    {
      if ($i == $page) {
        echo $i . ' ';
      }
      else {
        $page_link = '/index.php?page=' . $i;
        echo '<a href="' . $page_link . '">' . $i . ' </a>';
      }
    }

    echo '</div>';

    /*
    * $skip_users contains how much users should be skipped during selection.
    * For example, if you need page #3 to open, 5*(3-1) = 10 users should be skipped first.
    */
    $skip_users = 5 * ($page - 1);

    (new AdminInterface) -> showUserList($skip_users);
  }

  /*
  * showUserList() gets $skip_users variable, and then generates a table when the list of users is shown.
  */
  function showUserList($skip_users) {
    require 'includes/connect.php';
    require 'includes/db-arrays-fetch.php';

    $stmt = $pdo -> prepare('SELECT role_id, login, first_name, last_name, sex, birth_date FROM users ORDER BY id ASC LIMIT :skip_users, 5');
    // A little precision, because LIMIT clause takes only integer values.
    $stmt -> bindValue(':skip_users', $skip_users, PDO::PARAM_INT);
    $stmt -> execute();
    $users_array = $stmt -> fetchAll();

    // Generating the first table row and filling it with names.
    echo '
    <div>
    <table>
    <tr>
      <td>Role</td>
      <td>Login</td>
      <td>First name</td>
      <td>Last name</td>
      <td>Sex</td>
      <td>Birth date</td>
      <td></td>
    </tr>';

    //This variable relates to function located in db-arrays-fetch.php and contains the array of existing roles.
    $roles = (new FetchArray) -> generateRolesArray();

    /*
    * Every SELECT fetch returns the array with arrays in it.
    * For example,
    * $users_array = array( [0] => array( ['role_id'] => '1', ['login'] => 'name1' ),
    *                       [1] => array( ['role_id'] => '2', ['login'] => 'name2' ),
    *                       [2] => array( ['role_id'] => '1', ['login'] => 'name3' ) and so on...
    * The goal here is to reduce this contruction to one array and change number values of 'role_id' to role names,
    * which are contained in $roles array.
    */
    foreach ($users_array as $main_users_row => $inner_users_array) {
      // Changing number values of 'role_id' to role names containing in $roles.
      if (isset($roles[$inner_users_array['role_id']])) {
        $inner_users_array['role_id'] = $roles[$inner_users_array['role_id']];
      }

      // Generating and filling the table rows for each user. Values are brought from every array $users_array contains.
      echo '<tr>';
      foreach ($inner_users_array as $inner_users_row => $user_value) {
        echo '<td>' . $user_value . '</td>';
      }
      // Making links to edit and delete for each user.
      echo '<td><a href="/manage-accounts.php?action=edit&user=' . $inner_users_array['login'] .'">Edit</a></td>
      <td><a href="/manage-accounts.php?action=delete&user=' . $inner_users_array['login'] .'">Delete</a></td>';
      echo '</tr>';
    }
    echo '</table></div>';

  }
}

(new AdminInterface) -> openAdminInterface();
