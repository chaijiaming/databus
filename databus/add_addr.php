<?php

session_start();
$server = "localhost";
$username = "root";
$password = "";
$database = "database_phase3";
$connect = mysqli_connect($server, $username, $password, $database);
$street = mysqli_real_escape_string($connect, $_REQUEST['street']);
$apt = mysqli_real_escape_string($connect, $_REQUEST['apt']);
$zipcode = mysqli_real_escape_string($connect, $_REQUEST['zipcode']);
$state = mysqli_real_escape_string($connect, $_REQUEST['state']);

if(mysqli_connect_error())
{
    die("connection failed:" . mysqli_connect_error());
}

$max = "SELECT MAX(user_addr_id) AS MAXID FROM user_addr";
$max_result = mysqli_query($connect, $max);
$max_id = mysqli_fetch_assoc($max_result);
$max_id_increment = $max_id['MAXID'] + 1;

$sql_insert_user = "INSERT INTO user_addr(user_addr_id, street, apt, zip_code, state) VALUES('$max_id_increment','$street', '$apt', '$zipcode', '$state');";
$dis_result = mysqli_query($connect, $sql_insert_user);

$email = $_SESSION["email"];
$sql_update = "UPDATE regular SET regular_addr_id = '$max_id_increment' WHERE email = '$email'";
$upda = mysqli_query($connect, $sql_update);
if(!$dis_result){
    die( "sql failed:" . mysqli_error($connect));
}
else {

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
    <title>Address Added</title>
</head>
<div class=\"container\" style=\"padding: 30px 50px\">
  <div class=\"alert alert-success\">
    
    <strong>Address added!</strong>  Please wait while we are taking you back.
  </div>
</div>
";

}

mysqli_close($connect);
?>