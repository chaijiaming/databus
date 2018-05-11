<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 4/15/18
 * Time: 6:16 PM
 */

session_start();
$server = "localhost";
$username = "root";
$password = "";
$database = "database_phase3";
$conn = mysqli_connect($server, $username, $password, $database);
$space_name = mysqli_real_escape_string($conn, $_REQUEST['space_name']);
$street = mysqli_real_escape_string($conn, $_REQUEST['street']);
$apt = mysqli_real_escape_string($conn, $_REQUEST['apt']);
$state = mysqli_real_escape_string($conn, $_REQUEST['state']);
$zipcode = mysqli_real_escape_string($conn, $_REQUEST['zipcode']);
$availability_from = mysqli_real_escape_string($conn, $_REQUEST['from']);
$availability_till = mysqli_real_escape_string($conn, $_REQUEST['till']);
$categories =  mysqli_real_escape_string($conn, $_REQUEST['categories']);
$PricePerHour = mysqli_real_escape_string($conn, $_REQUEST['price']);
$description = mysqli_real_escape_string($conn, $_REQUEST['description']);
$amen = mysqli_real_escape_string($conn, $_REQUEST['amenities']);
$size = mysqli_real_escape_string($conn, $_REQUEST['size']);
$city = mysqli_real_escape_string($conn, $_REQUEST['city']);
$imgsrc = mysqli_real_escape_string($conn, $_REQUEST['img']);
if (!$conn) {
    die("Connection Failed: " .mysqli_connect_error());
}
$max = "SELECT MAX(space_id) AS MAXID FROM space";
$max_result = mysqli_query($conn, $max);
$max_id = mysqli_fetch_assoc($max_result);
$max_id_increment = $max_id['MAXID'] + 1;

$email = $_SESSION["email"];

$sql_get_username_password = "SELECT vendor_id FROM VENDOR WHERE email = '$email';";

$user_name_result = mysqli_query($conn, $sql_get_username_password);
$row = mysqli_fetch_assoc($user_name_result);

$ven_id = $row["vendor_id"];


//SQL Statements
$sql = "INSERT INTO Space (img, descr ,term_id , space_id, vendor_id, space_name,resv_start_date,resv_end_date,price,service) VALUES('$imgsrc','$description','$max_id_increment','$max_id_increment','$ven_id','$space_name','$availability_from','$availability_till','$PricePerHour','$amen');";
$sql1 = "INSERT INTO Space_addr (city, space_addr_id, street,room,state,zip_code) VALUES('$city','$max_id_increment','$street','$apt','$state','$zipcode');";
$sql2 = "INSERT INTO SUB_CATEGORY(sub_category_id, Category_id, size) VALUES('$max_id_increment', '$categories', $size);";


//Connecting SQL Query
//Checking if all required feilds are not filled

$sql_re = mysqli_query($conn, $sql);
$sql1_re = mysqli_query($conn, $sql1);
$sql2_re = mysqli_query($conn, $sql2);

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
    <title>Space Added</title>
</head>
<div class=\"container\" style=\"padding: 30px 50px\">
  <div class=\"alert alert-success\">
    
    <strong>Space added!</strong>  Please wait while we are taking you back.
  </div>
</div>
";

?>