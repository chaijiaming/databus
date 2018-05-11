<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 4/1/18
 * Time: 8:43 PM
 */

session_start();

session_unset();

session_destroy();
header("refresh:2;//localhost/databus/index.php");
echo "
<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <link rel=\"stylesheet\" type=\"text/css\" href=\"indexStyle.css\">
    <link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css\">

    <!-- jQuery library -->
    <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js\"></script>

    <!-- Latest compiled JavaScript -->
    <script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js\"></script>
    <title>Login</title>
</head>
<div class=\"container\" style=\"padding: 30px 50px\">
<h3>Oh...We will miss you!</h3>
  <div class=\"alert alert-warning\">
    
    <strong>Hope you will come back!</strong>
  </div>
</div>
";