<!doctype_html>
<html lang="en">
<head>
  <meta charset="utf-8">

  <link rel="stylesheet" type="text/css" href="styles.css" />

  <title><?php echo isset($_SESSION['page_title']) ? htmlspecialchars($_SESSION['page_title']) : 'User Interfaces'; ?></title>
</head>
<body>
  <div class="main">

<?php

/*
* Generates the HTML and takes page titles from $_SESSION['page_title'].
* It also throws error messages and notifications from $_SESSION['errorMsg'].
*/
function checkError() {
  if ($_SESSION['errorMsg']) {
    echo '<div class="errorMsg">'. $_SESSION['errorMsg'] . '</div>';
    unset($_SESSION['errorMsg']);
  }
}

checkError();
