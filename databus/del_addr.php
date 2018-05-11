<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 4/15/18
 * Time: 5:34 PM
 */
session_start();
$server = "localhost";
$username = "root";
$password = "";
$database = "database_phase3";
$connect = mysqli_connect($server, $username, $password, $database);

if(mysqli_connect_error())
{
    die("connection failed:" . mysqli_connect_error());
}
$email = $_SESSION['email'];
$selec = "SELECT regular_addr_id FROM regular where email = '$email'";
$se_result = mysqli_query($connect, $selec);
$se_res = mysqli_fetch_assoc($se_result);
$addr_id = $se_res['regular_addr_id'];
$zero = 0;
$update_id = "UPDATE regular SET regular_addr_id = '$zero' WHERE email = '$email'";
$upda = mysqli_query($connect, $update_id);

$del = "DELETE FROM user_addr WHERE user_addr_id = '$addr_id'";
$del_rel = mysqli_query($connect, $del);
header("refresh:2;//localhost/databus/me.php");
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
    <title>Address Deleted</title>
</head>
<div class=\"container\" style=\"padding: 30px 50px\">
  <div class=\"alert alert-success\">
    
    <strong>Address Deleted</strong>  Please wait while we are taking you back.
  </div>
</div>
";