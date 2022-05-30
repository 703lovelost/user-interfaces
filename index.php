<?php

/*
* Loads the log-in page or interfaces, depends on user is logged in already or not.
*/

  session_start();

  // Contains main html-content.
  require_once 'includes/header.php';

  class Main {
    function checkUserStatus() {

      /*
      * If user starts application for the first time, he needs a status for system to identify.
      * Then the log-in page is loaded. It also has its title, which is read by header.php
      * It's also useful when user's logged out, because every SESSION variable was deleted during the log out process.
      */

      if (!$_SESSION['user_status']) {
        $_SESSION['user_status'] = 'guest';
        $_SESSION['page_title'] = 'Log in';
        require_once 'login.php';
      }

      /*
      * If user's status is already identified by the system, it loads log-in page with related title as well.
      */

      if ($_SESSION['user_status'] == 'guest') {
        $_SESSION['page_title'] = 'Log in';
        require_once 'login.php';
        (new Authentication) -> checkAuth();
      }

      /*
      * If user is already or just logged in, the interface will be loaded, depending on what $_SESSION['user_status'] was set in auth.php
      */

      if ($_SESSION['user_status'] == 'client') {
        $_SESSION['page_title'] = 'Client Interface';
        require_once 'client_interface.php';
      }

      if ($_SESSION['user_status'] == 'admin') {
        $_SESSION['page_title'] = 'Admin Interface';
        require_once 'admin_interface.php';
      }
    }
  }

  (new Main) -> checkUserStatus();
