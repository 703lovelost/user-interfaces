<?php
session_start();

class ClientInterface {

  /*
  * If I were a user without admin privileges, I would be really dissappointed.
  * That's why I call a function that way.
  * Too bad, there's nothing to be shown to average user, except his name and log out link.
  */

  function dissappointNonAdmin() {

    echo '<div>There\'s nothing available for you, ' . $_SESSION['name'] . '
    <br>
    <a href="/logout.php">Log out</a></div>';
  }
}

(new ClientInterface) -> dissappointNonAdmin();
