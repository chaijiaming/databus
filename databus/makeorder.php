<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 4/22/18
 * Time: 1:34 PM
 */
function get_string($str){
    $day = substr($str, 3,2);
    $month = substr($str, 0,2);
    $year = substr($str, 6,4);
    $entry = $year ."-". $month. "-" . $day;
    return $entry;

}

session_start();
$server = "localhost";
$username = "root";
$password = "";
$database = "database_phase3";
$conn = mysqli_connect($server, $username, $password, $database);
$last_name = mysqli_real_escape_string($conn, $_REQUEST['lastName']);
$price = mysqli_real_escape_string($conn, $_REQUEST['price']);
$regular_id = $_SESSION['id'];
$space_id = $_SESSION['space_id'];
$range_date = mysqli_real_escape_string($conn, $_REQUEST['daterange']);
$myarr = explode(' - ', $range_date);

$start_date =  $myarr[0];
$end_date = $myarr[1];
$start_date = get_string($start_date);
$end_date = get_string($end_date);

$cardname = mysqli_real_escape_string($conn, $_REQUEST['cc-name']);
$cardnum = mysqli_real_escape_string($conn, $_REQUEST['cc-number']);
$cardexp = mysqli_real_escape_string($conn, $_REQUEST['cc-expiration']);
$cardcvv = mysqli_real_escape_string($conn, $_REQUEST['cc-cvv']);

if (!$conn) {
    die("Connection Failed: " .mysqli_connect_error());
}

$max = "SELECT MAX(Trans_id) AS MAXID FROM transaction";
$max_result = mysqli_query($conn, $max);
$max_id = mysqli_fetch_assoc($max_result);
$max_id_increment = $max_id['MAXID'] + 1;
$trans = $max_id_increment;

$term_sql = "INSERT INTO transaction ( Trans_id, Regular_id, Space_id, start_date, end_date, price) 
VALUES ('$max_id_increment', '$regular_id', '$space_id','$start_date', '$end_date','$price' );  ";

$term_res = mysqli_query($conn, $term_sql);

$max = "SELECT MAX(Cards_id) AS MAXID FROM cards";
$max_result = mysqli_query($conn, $max);
$max_id = mysqli_fetch_assoc($max_result);
$max_id_increment = $max_id['MAXID'] + 1;

$cards_sql = "INSERT INTO cards (Cards_id, card_num, expire_date, cvv, regular_id, trans_id) VALUES( '$max_id_increment', '$cardnum', '$cardexp', '$cardcvv', '$regular_id', '$trans' );";
$cards_res = mysqli_query($conn, $cards_sql);


$max = "SELECT MAX(Term_id) AS MAXID FROM terms";
$max_result = mysqli_query($conn, $max);
$max_id = mysqli_fetch_assoc($max_result);
$max_id_increment = $max_id['MAXID'] + 1;

$ter_sql = "INSERT INTO terms(Term_id, Space_id, end_date, start_date) VALUES ('$max_id_increment', '$space_id', '$end_date', '$start_date');";
$ter_res = mysqli_query($conn, $ter_sql);

$points = $price * 0.1;


$points_sql = "UPDATE regular SET points = points + '$points' WHERE Regular_id = '$regular_id';";
$points_res = mysqli_query($conn, $points_sql);

$ven_id = "SELECT Vendor_id FROM space WHERE Space_id = '$space_id';";
$ven_result = mysqli_query($conn, $ven_id);
$vendor_id_row = mysqli_fetch_assoc($ven_result);
$vendor_id = $vendor_id_row['Vendor_id'];

$add_money_sql = "UPDATE vendor SET balance = balance + '$price' WHERE Vendor_id = '$vendor_id';";
$add_res = mysqli_query($conn, $add_money_sql);


header("refresh:3;//localhost/databus/me.php");

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
    <title>Order Confirmed</title>
</head>
<div class=\"container\" style=\"padding: 30px 50px\">
<h3>We are processing your order.</h3>
  <div class=\"alert alert-success\">
    
    <strong>Order has placed!</strong>  Please wait while we are taking you to your order page.
  </div>
</div>
";










