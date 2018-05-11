<?php
session_start();
$server = "localhost";
$username = "root";
$password = "";
$database = "database_phase3";
$connect = mysqli_connect($server, $username, $password, $database);
$email = mysqli_real_escape_string($connect, $_REQUEST['email']);
$name = mysqli_real_escape_string($connect, $_REQUEST['name']);
$pass = mysqli_real_escape_string($connect, $_REQUEST['password']);
$phone = mysqli_real_escape_string($connect, $_REQUEST['phone']);
$company = mysqli_real_escape_string($connect, $_REQUEST['company']);
$last = mysqli_real_escape_string($connect, $_REQUEST['last']);

if(mysqli_connect_error())
{
    die("connection failed:" . mysqli_connect_error());
}
$max = "SELECT MAX(Vendor_id) AS MAXID FROM VENDOR";
$max_result = mysqli_query($connect, $max);
$max_id = mysqli_fetch_assoc($max_result);
$max_id_increment = $max_id['MAXID'] + 1;

$sql_insert_user = "INSERT INTO VENDOR(Vendor_id,first_name, last_name, email, password, phone_number, company, balance) VALUES('$max_id_increment','$name', '$last', '$email', '$pass', '$phone', '$company', 0);";
$dis_result = mysqli_query($connect, $sql_insert_user);
if(!$dis_result){
    die( "sql failed:" . mysqli_error($connect));
}
else {
    $_SESSION['email'] = $email;
    $_SESSION['first_name'] = $name;
    $_SESSION['company'] = $company;
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
    <title>Sign up</title>
</head>
<div class=\"container\" style=\"padding: 30px 50px\">
  <div class=\"alert alert-success\">
    
    <strong>Welcome to Databus.com</strong>  Please wait while we are taking you back.
  </div>
</div>
";

}

mysqli_close($connect);
?>