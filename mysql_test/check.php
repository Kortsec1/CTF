<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>index page</title>
  </head>
  <body>
    <?php
      $mysql_hostname = "192.168.75.149";
      $mysql_user = "study_user";
      $mysql_password = "study";
      $mysql_database = "study_db";

      $bd = mysql_connect($mysql_hostname, $mysql_user, $mysql_password) or die("db connect error");
      mysql_select_db($mysql_database, $bd) or die("db connect error");

      $id = $_GET['id'];
      $pw = $_GET['pw'];

      $sql="SELECT user_id FROM users WHERE user_id = '$id' AND user_pw = '$pw';"
      $result = mysql_query($sql);
      $auth = mysql_fetch_array($result);

      if (!$result) {
        echo "login failed..";
      }

      else {
        echo "Hello ".$auth;
      }
     ?>
  </body>
</html>