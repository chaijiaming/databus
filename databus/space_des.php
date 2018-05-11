<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 4/10/18
 * Time: 8:21 PM
 */
$P_id = 'page1';

$server = "localhost";
$username = "root";
$password = "";
$database = "database_phase3";
$connect = mysqli_connect($server, $username, $password, $database);
if(mysqli_connect_error())
{
    die("connection failed:" . mysqli_connect_error());
}
$sql_get_discription = "SELECT Description, create_time, picture, Vendor_id FROM Pages WHERE Page_id = '$P_id';";
$dis_result = mysqli_query($connect, $sql_get_discription);
if(!$dis_result){
    die( "sql failed:" . mysqli_error($connect));
}
$dis_row = mysqli_fetch_assoc($dis_result);
$vendor_id = $dis_row["Vendor_id"];
$sql_get_vendor_info = "SELECT first_name, last_name, email, password, phone_number, company, balance FROM Vendor WHERE Vendor_id = '$vendor_id';";
$vendor_result = mysqli_query($connect, $sql_get_vendor_info);
if(!$vendor_result){
    die( "sql failed:" . mysqli_error($connect));
}
$vendor_row = mysqli_fetch_assoc($vendor_result);
// $vendor_first_name = $vendor_row["first_name"];
// $vendor_last_name = $vendor_row["last_name"];
// $vendor_email = $vendor_row["email"];
// $vendor_first_name = $vendor_row["first_name"];
$dis = $dis_row["Description"];
$time = $dis_row["create_time"];
$pic = $dis_row["picture"];
//echo "password correct!<br>";
print "desciption   " . $dis . "<br>";
print "create time  " . $time . "<br>";
print "picture   " . $pic . "<br>";
print "vendor  " . $vendor_id . "<br>";
print "<br><br><br>";
print "vendor info:";
print "first name: " . $vendor_row["first_name"] . "<br>";
print "last time  " . $vendor_row["last_name"] . "<br>";
print "email   " . $vendor_row["email"] . "<br>";
print "password  " . $vendor_row["password"] . "<br>";
print "phone number  " . $vendor_row["phone_number"] . "<br>";
print "company  " . $vendor_row["company"] . "<br>";
print "balance  " . $vendor_row["balance"] . "<br>";
echo '<img src="/Users/mhy/Desktop/google_space.png">';