<?php
session_start();

class Logout {

  /*
  * abortSession() deletes the SESSION variables, making interfaces unavailable without logging in again, and then redirects to index.php
  */

  function abortSession() {
    session_destroy();
    header('Location: /index.php');
    exit();
  }
}

(new Logout) -> abortSession();
